<?php

namespace App\Legacy;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\TransportJob;

class FxHeader extends Model {

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'legacy';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'FX_Header';

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /*
     * No timestamps.
     */
    public $timestamps = false;

    /**
     * A shipment has many packages
     *
     * @return
     */
    public function packages()
    {
        return $this->hasMany(FxAwb::class, 'MATxID', 'TxID');
    }

    /**
     * Sets the shipment to received - updates associated packages and inserts a tracking event for each package
     *
     * @return null
     */
    public function setReceived($dateReceived = null, $userId = 0, $carrierScan = false, $updatePackages = true)
    {
        // Already received
        if ($this->Rec_IFS == 'Y') {
            return false;
        }

        $dateReceived = toCarbon($dateReceived, 'Europe/London');

        $this->Rec_IFS = 'Y';
        $this->Ship_Date = $dateReceived->toDateString();
        $this->Rec_IFS_Date = $dateReceived->toDateString();
        $this->Rec_IFS_Time = $dateReceived->toTimeString();
        $this->complete = 'Y';
        $this->save();

        if ($updatePackages) {
            // Update the package records to "received"
            foreach ($this->packages as $package) {
                $package->setReceived($dateReceived, $userId, $carrierScan);
            }
        }

        // Close the collection request
        $transportJob = TransportJob::whereReference($this->TxID)->whereType('c')->whereCompleted(0)->first();

        if ($transportJob) {
            $transportJob->close($dateReceived);
        }

        // Create a delivery request (if required)
        $this->createDeliveryRequest();

        return true;
    }

    /**
     * Create a delivery request.
     *
     */
    public function createDeliveryRequest()
    {
        
        return true;
        
        // Don't create a delivery request if these conditions are met
        if ($this->IFSDepot != 'ANT' || $this->complete != 'Y' || TransportJob::whereType('d')->whereReference($this->TxID)->count() > 0) {

            return false;
        }

        // Don't create a delivery request if these conditions are met
        if (!stristr($this->gateway, 'IFS')) {

            $visible = false;
        } else {

            $visible = true;
        }

        if (isset($this->Cnor_Comp) && $this->Cnor_Comp > "") {

            $company = $this->Cnor_Comp;
        } else {

            if (isset($this->Cnor_Contact) && $this->Cnor_Contact > "") {

                $company = $this->Cnor_Contact;
            } else {

                $company = "IFS Global Logistics Ltd";
            }
        }

        $transportJob = TransportJob::create([
                    'number' => \App\Sequence::whereCode('JOB')->lockForUpdate()->first()->getNextAvailable(),
                    'reference' => $this->TxID,
                    'pieces' => $this->Total_Pkgs,
                    'weight' => $this->Wght,
                    // 'goods_description' => $this->goodsDesc,
                    'volumetric_weight' => $this->Vol_Wght,
                    'instructions' => 'Deliver to: ' . $this->Cnee_Contact . ', ' . $this->Cnee_Address1 . ', ' . $this->Cnee_Town . ' ' . $this->Cnee_PostCode,
                    'scs_job_number' => $this->JobDisp,
                    'cash_on_delivery' => 0,
                    'type' => 'd',
                    'from_type' => 'c',
                    'from_name' => 'Transport Department',
                    'from_company_name' => $company,
                    'from_address1' => 'c\o IFS Global Logistics',
                    'from_address2' => 'Seven Mile Straight',
                    'from_city' => 'Antrim',
                    'from_state' => 'County Antrim',
                    'from_postcode' => 'BT41 4QE',
                    'from_country_code' => 'GB',
                    'from_telephone' => '02894 464211',
                    'from_email' => 'transport@antrim.ifsgroup.com',
                    'to_type' => 'c',
                    'to_name' => $this->Cnee_Contact,
                    'to_company_name' => $this->Cnee_Comp,
                    'to_address1' => $this->Cnee_Addr1,
                    'to_address2' => $this->Cnee_Addr2,
                    'to_address3' => $this->Cnee_Addr3,
                    'to_city' => $this->Cnee_City,
                    'to_state' => $this->Cnee_State,
                    'to_postcode' => $this->Cnee_PostCode,
                    'to_country_code' => $this->Cnee_Country,
                    'to_telephone' => $this->Cnee_Phone,
                    'to_email' => $this->Cnee_Email,
                    'depot_id' => 1,
                    'visible' => $visible,
                    'date_requested' => Carbon::now()->addWeekday()
        ]);

        $transportJob->setStatus('unmanifested');
    }

    /**
     * Cancel a shipment.
     *
     * @return boolean
     */
    public function setCancelled()
    {
        if ($this->complete == 'C') {
            return false;
        }

        $this->complete = 'C';
        $this->save();

        // Add cancellation event to ShipTrack                               
        $ShipTrack = new \App\Legacy\ShipTrack();
        $ShipTrack->depot = $this->gateway;
        $ShipTrack->docketno = $this->TxID;
        $ShipTrack->datetime = date('Y-m-d H:i:s', time());
        $ShipTrack->status = 'Shipment Cancelled';
        $ShipTrack->eventtype = 'Cancelled';
        $ShipTrack->source = 'LAR';
        $ShipTrack->save();
    }

    /**
     * Undo cancel.
     *
     * @return boolean
     */
    public function undoCancel()
    {
        if ($this->complete != 'C') {
            return false;
        }

        if ($this->Rec_IFS == 'Y') {
            $this->complete = 'Y';
        } else {
            $this->complete = 'N';
        }

        $this->save();

        // Delete the cancel tracking event within ShipTrack
        $shipTrack = \App\Legacy\ShipTrack::where('docketno', $this->TxID)->where('eventtype', 'Cancelled')->delete();
    }

    /**
     * Returns the number of packages scanned i.e "1 of 2"
     *
     * @return string
     */
    public function getPackageScanCount()
    {
        $count = 0;
        foreach ($this->packages as $package) {
            if ($package->Rec_IFS == 'Y') {
                $count++;
            }
        }

        return $count . ' of ' . $this->Total_Pkgs;
    }

}
