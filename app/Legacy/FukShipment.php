<?php

namespace App\Legacy;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\TransportJob;

class FukShipment extends Model {

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
    protected $table = 'FUKShipment';

    /*
     * No timestamps.
     */
    public $timestamps = false;

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /**
     * A shipment has many packages
     *
     * @return
     */
    public function packages()
    {
        return $this->hasMany(FukPackage::class, 'docketno', 'docketno');
    }

    /**
     * A shipment has many tracking events
     *
     * @return
     */
    public function tracking()
    {
        return $this->hasMany(ShipTrack::class, 'docketno', 'docketno');
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
        $transportJob = TransportJob::whereReference($this->docketno)->whereType('c')->whereCompleted(0)->first();

        if ($transportJob) {
            $transportJob->close($dateReceived);
        }

        // Create a delivery request (if required)
        $this->createDeliveryRequest();

        return true;
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
        $ShipTrack->docketno = $this->docketno;
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
        $shipTrack = \App\Legacy\ShipTrack::where('docketno', $this->docketno)->where('eventtype', 'Cancelled')->delete();
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
            if ($package->scanned == 'Y') {
                $count++;
            }
        }

        return $count . ' of ' . $this->pieces;
    }

    /**
     * Create a delivery request.
     *
     */
    public function createDeliveryRequest()
    {
        return true;
        
        $visible = true;

        // Don't create a delivery request if these conditions are met
        if ($this->IFSDepot != 'ANT' || $this->complete != 'Y' || TransportJob::whereType('d')->whereReference($this->docketno)->count() > 0) {
            return false;
        }

        // Don't create a delivery request if these conditions are met
        if (!stristr($this->gateway, 'IFS')) {
            $visible = false;
        }

        if (isset($this->cnorCompany) && $this->cnorCompany > "") {

            $company = $this->cnorCompany;
        } else {

            if (isset($this->cnorContact) && $this->cnorContact > "") {
                
                $company = $this->cnorContact;
            } else {
                
                $company = "IFS Global Logistics Ltd";
            }
        }

        $transportJob = TransportJob::create([
                    'number' => \App\Sequence::whereCode('JOB')->lockForUpdate()->first()->getNextAvailable(),
                    'reference' => $this->docketno,
                    'pieces' => $this->pieces,
                    'weight' => $this->weight,
                    'goods_description' => $this->goodsDesc,
                    'volumetric_weight' => $this->volWeight,
                    'instructions' => 'Deliver to: ' . $this->cneeContact . ', ' . $this->cneeAddress1 . ', ' . $this->cneeTown . ' ' . $this->cneePostCode,
                    'scs_job_number' => $this->ifsjobref,
                    'cash_on_delivery' => 0,
                    'type' => 'd',
                    'from_type' => 'c',
                    'from_name' => 'Transport Department',
                    'from_company_name' => $company,
                    'from_address1' => 'Seven Mile Straight',
                    'from_city' => 'Antrim',
                    'from_state' => 'County Antrim',
                    'from_postcode' => 'BT41 4QE',
                    'from_country_code' => 'GB',
                    'from_telephone' => '02894 464211',
                    'from_email' => 'transport@antrim.ifsgroup.com',
                    'to_type' => 'c',
                    'to_name' => $this->cneeContact,
                    'to_company_name' => $this->cneeCompany,
                    'to_address1' => $this->cneeAddress1,
                    'to_address2' => $this->cneeAddress2,
                    'to_address3' => $this->cneeAddress3,
                    'to_city' => $this->cneeTown,
                    'to_state' => $this->cneeCounty,
                    'to_postcode' => $this->cneePostCode,
                    'to_country_code' => $this->cneeCountry,
                    'to_telephone' => $this->cneePhone,
                    'to_email' => $this->cneeEmail,
                    'depot_id' => 1,
                    'visible' => $visible,
                    'date_requested' => Carbon::now()->addWeekday()
        ]);

        $transportJob->setStatus('unmanifested');
    }

    /**
     * Set shipment to delivered.
     *
     * @param type $podDate
     * @param type $podSignature
     */
    public function setDelivered($podDate, $podSignature)
    {
        $podDate = toCarbon($podDate, 'Europe/London');

        $this->POD_Flag = 'Y';
        $this->POD_Date = $podDate->toDateString();
        $this->POD_Time = $podDate->toTimeString();
        $this->Signature = $podSignature;
        $this->save();

        $ShipTrack = new \App\Legacy\ShipTrack();
        $ShipTrack->depot = $this->gateway;
        $ShipTrack->docketno = $this->docketno;
        $ShipTrack->pod = strtoupper($podSignature);
        $ShipTrack->datetime = $podDate;
        $ShipTrack->status = 'Delivered';
        $ShipTrack->eventtype = 'PROOF OF DELIVERY';
        $ShipTrack->location = $this->cneeTown;
        $ShipTrack->city = $this->cneeTown;
        $ShipTrack->countrycode = 'GB';
        $ShipTrack->source = 'LAR';
        $ShipTrack->save();
    }

    /**
     * Reverse out the actions of setDelivered
     */
    public function undoSetDelivered()
    {
        $this->POD_Flag = 'N';
        $this->POD_Date = null;
        $this->POD_Time = null;
        $this->Signature = null;
        $this->save();

        // Delete the tracking record
        \App\Legacy\ShipTrack::where('depot', $this->gateway)
                ->where('docketno', $this->docketno)
                ->where('status', 'Delivered')
                ->delete();
    }

}
