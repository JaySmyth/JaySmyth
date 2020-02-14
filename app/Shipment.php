<?php

namespace App;

use App\Country;
use App\Legacy\FukShipment;
use App\Mail\GenericError;
use App\Mail\TransportJobReinstated;
use App\Pricing\Pricing;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\PdfToImage\Pdf;

class Shipment extends Model
{
    use ShipmentScopes,
        ShipmentAlerting,
        Logable;

    protected $fillable = [
        'consignment_number',
        'carrier_consignment_number',
        'carrier_tracking_number',
        'shipment_reference',
        'order_number',
        'token',
        'source',
        'pieces',
        'weight',
        'weight_uom',
        'dims_uom',
        'volumetric_weight',
        'volumetric_divisor',
        'customs_value',
        'customs_value_currency_code',
        'documents_description',
        'goods_description',
        'special_instructions',
        'max_dimension',
        'received',
        'pallet',
        'delivered',
        'pod_signature',
        'scs_job_number',
        'invoicing_status',
        'shipping_charge',
        'shipping_cost',
        'fuel_charge',
        'fuel_cost',
        'cost_currency',
        'sales_currency',
        'quoted',
        'carrier_pickup_required',
        'insurance_value',
        'lithium_batteries',
        'alcohol_type',
        'alcohol_packaging',
        'alcohol_volume',
        'alcohol_quantity',
        'dry_ice_flag',
        'dry_ice_weight_per_package',
        'dry_ice_total_weight',
        'hazardous',
        'external_tracking_url',
        'sender_type',
        'sender_name',
        'sender_company_name',
        'sender_address1',
        'sender_address2',
        'sender_address3',
        'sender_city',
        'sender_state',
        'sender_postcode',
        'sender_country_code',
        'sender_telephone',
        'sender_email',
        'recipient_type',
        'recipient_name',
        'recipient_company_name',
        'recipient_address1',
        'recipient_address2',
        'recipient_address3',
        'recipient_city',
        'recipient_state',
        'recipient_postcode',
        'recipient_country_code',
        'recipient_telephone',
        'recipient_email',
        'ship_reason',
        'terms_of_sale',
        'invoice_type',
        'ultimate_destination_country_code',
        'eori',
        //'consignee_ein',
        'commercial_invoice_comments',
        'bill_shipping',
        'bill_tax_duty',
        'bill_shipping_account',
        'bill_tax_duty_account',
        'broker_name',
        'broker_company_name',
        'broker_address1',
        'broker_address2',
        'broker_city',
        'broker_state',
        'broker_postcode',
        'broker_country_code',
        'broker_telephone',
        'broker_email',
        'broker_id',
        'broker_account',
        'legacy',
        'form_values',
        'user_id',
        'company_id',
        'status_id',
        'mode_id',
        'department_id',
        'carrier_id',
        'service_id',
        'route_id',
        'depot_id',
        'manifest_id',
        'invoice_run_id',
        'collection_date',
        'ship_date',
        'delivery_date',
        'created_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['collection_date', 'ship_date', 'delivery_date', 'created_at', 'updated_at'];

    /**
     * Set the shipment reference.
     *
     * @param string $value
     * @return string
     */
    public function setShipmentReferenceAttribute($value)
    {
        $this->attributes['shipment_reference'] = strtoupper($value);
    }

    /**
     * Set the shipment reference.
     *
     * @param string $value
     * @return string
     */
    public function setSenderTypeAttribute($value)
    {
        $this->attributes['sender_type'] = strtolower($value);
    }

    /**
     * Set the shipment reference.
     *
     * @param string $value
     * @return string
     */
    public function setEoriAttribute($value)
    {
        $this->attributes['eori'] = strtoupper($value);
    }

    /**
     * Calculate how long a shipment has been in transit.
     *
     * @return int
     */
    public function getTimeInTransitAttribute()
    {
        $timeInTransit = 0;

        if ($this->received || $this->delivered || $this->mode_id == 2) {
            $startTime = strtotime($this->ship_date);

            // Delivery date is before ship date (not scanned at IFS) - use created_at + 8hrs
            if ($this->delivery_date && $this->delivery_date < $this->ship_date) {
                $startTime = strtotime($this->created_at->addHours(8));
            }

            if ($startTime > 0) {
                if (! $this->delivery_date && in_array($this->status_id, [3, 4, 5, 8])) {
                    $finishTime = time();
                } else {
                    $finishTime = strtotime($this->delivery_date);
                }

                $diff = $finishTime - $startTime;

                if ($diff < 0) {
                    return 0;
                }

                $oneDay = 86400; //number of seconds in the day
                $oneHour = 3600; //number of seconds in an hour

                if ($diff < $oneDay) {
                    return round($diff / $oneHour, 0, PHP_ROUND_HALF_DOWN);
                }

                $removeHours = 0;
                while ($startTime <= $finishTime) {
                    if (date('N', $startTime) > 5) { // If weekend (6,7)
                        $removeHours++;
                    }
                    $startTime += $oneHour; //increment by one hour
                }

                $timeInTransit = round(($diff - ($removeHours * $oneHour)) / $oneHour, 0, PHP_ROUND_HALF_DOWN);

                if ($timeInTransit < 0) {
                    $timeInTransit = 0;
                }
            }
        }

        return $timeInTransit;
    }

    /**
     * Get the sender's country.
     *
     * @return string
     */
    public function getSenderCountryAttribute()
    {
        return getCountry($this->sender_country_code);
    }

    /**
     * Get the recipient's country.
     *
     * @return string
     */
    public function getRecipientCountryAttribute()
    {
        return getCountry($this->recipient_country_code);
    }

    /**
     * Get the recipient's country.
     *
     * @return string
     */
    public function getShipperAttribute()
    {
        if ($this->company) {
            return $this->company->company_name;
        }
    }

    /**
     * Get the recipient's country.
     *
     * @return string
     */
    public function getLegacyPricingAttribute()
    {
        if ($this->company) {
            return $this->company->legacy_pricing;
        }
    }

    /**
     * Get the chargeable weight.
     *
     * @return string
     */
    public function getChargeableWeightAttribute()
    {
        if ($this->volumetric_weight > $this->weight) {
            return round($this->volumetric_weight, 1);
        }

        return round($this->weight, 1);
    }

    /**
     * Express the tracking progress of a shipment as a percentage.
     *
     * @return int
     */
    public function getProgressAttribute()
    {
        switch ($this->status->code) {
            case 'in_transit':
                return 50;
            case 'out_for_delivery':
                return 75;
            case 'delivered':
            case 'return_to_sender':
                return 100;
            default:
                return 0;
        }
    }

    /**
     * Get carrier tracking URL.
     *
     * @return int
     */
    public function getCarrierTrackingUrlAttribute()
    {
        $url = null;

        switch ($this->carrier_id) {

            // IFS Air Freight
            case 1:
                // schenker tracking for CDE
                if ($this->company_id == 314 && $this->service_id == 4 && stristr($this->carrier_consignment_number, '-')) {
                    $url = "https://apps.dbschenkerusa.com/apps/Tracking/SchenkerDetail.aspx?rt=aw&rn=$this->carrier_consignment_number";
                }
                break;

            // Fedex
            case 2:
                $url = "https://www.fedex.com/apps/fedextrack/?action=track&tracknumbers=$this->carrier_tracking_number&locale=en_GB&cntry_code=gb";
                break;
            // UPS
            case 3:
                if (stristr($this->carrier_tracking_number, '1Z')) {
                    $url = "http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=$this->carrier_tracking_number";
                }
                break;
            // TNT
            case 4:
                $url = "https://www.tnt.com/express/en_gb/site/shipping-tools/tracking.html?searchType=con&cons=$this->carrier_tracking_number";
                break;
            // DHL
            case 5:
                $url = "http://www.dhl.co.uk/en/express/tracking.html?AWB=$this->carrier_tracking_number&brand=DHL";
                break;
            // USPS
            case 11:
                $url = "https://tools.usps.com/go/TrackConfirmAction?tRef=fullpage&tLc=2&text28777=&tLabels=$this->carrier_tracking_number";
                break;
            // Primary Freight
            case 12:
                if (substr($this->carrier_tracking_number, 0, 2) == '1Z') {
                    $url = "http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=$this->carrier_tracking_number";
                } elseif (substr($this->carrier_tracking_number, 0, 1) == 7 && strlen($this->carrier_tracking_number) == 12) {
                    $url = "https://www.fedex.com/apps/fedextrack/?action=track&tracknumbers=$this->carrier_tracking_number&locale=en_GB&cntry_code=gb";
                } else {
                    //$url = "https://tools.usps.com/go/TrackConfirmAction?tRef=fullpage&tLc=2&text28777=&tLabels=$this->carrier_tracking_number";
                    $url = false;
                }
                break;
            // Express Freight
            case 14:
                $url = "https://track.anpost.ie/TrackingResults.aspx?rtt=1&items=$this->carrier_tracking_number";
                break;
            default:
                break;
        }

        return $url;
    }

    /**
     * Get print url.
     */
    public function getPrintUrlAttribute()
    {
        if (! $this->legacy) {
            return url('/label', $this->token);
        }

        if ($this->isUkDomestic()) {
            $FukShipment = FukShipment::select('id')->where('docketno', $this->carrier_consignment_number)->where('compID', $this->company_id)->first();

            if ($FukShipment) {
                return 'https://www.ifsgl.com/CourierUK/reprint.php?id='.$FukShipment->id.'&format=A4';
            } else {
                return;
            }
        }

        return 'https://www.ifsgl.com/Courier/print.php?id='.$this->carrier_consignment_number;
    }

    /**
     * Determines if a shipment is classified as UK domestic.
     *
     * @param type $senderCountryCode
     * @param type $recipientCountryCode
     * @return bool
     */
    public function isUkDomestic()
    {
        if (in_array($this->sender_country_code, getUkDomesticCountries()) && in_array($this->recipient_country_code, getUkDomesticCountries())) {
            return true;
        }

        return false;
    }

    /**
     * Get source timezone.
     *
     * @return string
     */
    public function getSourceTimezoneAttribute()
    {
        return getTimezone($this->sender_country_code, $this->sender_state, $this->sender_city);
    }

    /**
     * Get destination timezone.
     *
     * @return string
     */
    public function getDestinationTimezoneAttribute()
    {
        return getTimezone($this->recipient_country_code, $this->recipient_state, $this->recipient_city);
    }

    /**
     * Get quoted field as an array.
     *
     * @return array
     */
    public function getQuotedArrayAttribute()
    {
        return json_decode($this->quoted, true);
    }

    /**
     * Gross margin.
     *
     * @return string
     */
    public function getMarginAttribute()
    {
        if ($this->shipping_charge > 0) {
            $margin = ($this->shipping_charge - $this->shipping_cost) / $this->shipping_charge * 100;

            return number_format($margin, 2).'%';
        }

        return 'n/a';
    }

    /**
     * Determine if there has been an overcharge and who by.
     *
     * @return mixed
     */
    public function getMarginStylingClassAttribute()
    {
        if ($this->shipping_cost > $this->shipping_charge) {
            return 'text-danger';
        }

        if ($this->shipping_charge > $this->shipping_cost) {
            return 'text-success';
        }

        return 'text-primary';
    }

    /**
     * Return the difference.
     *
     * @return type
     */
    public function getProfitAttribute()
    {
        return abs($this->shipping_cost - $this->shipping_charge);
    }

    /**
     * Return the difference.
     *
     * @return type
     */
    public function getProfitFormattedAttribute()
    {
        if ($this->shipping_cost > $this->shipping_charge) {
            return '-'.number_format($this->profit, 2);
        } elseif ($this->shipping_charge > $this->shipping_cost) {
            return '+'.number_format($this->profit, 2);
        } else {
            return number_format($this->profit, 2);
        }
    }

    /**
     * A shipment has many packages.
     *
     * @return
     */
    public function packages()
    {
        return $this->hasMany(Package::class)->orderBy('index');
    }

    /**
     * A shipment has many contents.
     *
     * @return
     */
    public function contents()
    {
        return $this->hasMany(\App\ShipmentContent::class)->orderBy('package_index');
    }

    /**
     * A shipment has one service.
     *
     * @return
     */
    public function service()
    {
        return $this->belongsTo(\App\Service::class);
    }

    /**
     * A shipment is owned by a company.
     *
     * @return
     */
    public function company()
    {
        return $this->belongsTo(Company::class)->with('depot');
    }

    /**
     * A shipment is owned by a user.
     *
     * @return
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    /**
     * A shipment has one carrier.
     *
     * @return
     */
    public function carrier()
    {
        return $this->belongsTo(\App\Carrier::class);
    }

    /**
     * A shipment is owned by a route.
     *
     * @return
     */
    public function route()
    {
        return $this->belongsTo(\App\Route::class);
    }

    /**
     * A shipment is owned by a depot.
     *
     * @return
     */
    public function depot()
    {
        return $this->belongsTo(\App\Depot::class);
    }

    /**
     * A shipment hasone manifest.
     *
     * @return
     */
    public function manifest()
    {
        return $this->belongsTo(\App\Manifest::class);
    }

    /**
     * A shipment has many pricing manifests.
     *
     * @return
     */
    public function pricingManifest()
    {
        return $this->hasMany('App\PricingManifest');
    }

    /**
     * A shipment has one label.
     *
     * @return
     */
    public function label()
    {
        return $this->hasOne(Label::class);
    }

    /**
     * A shipment belongs to a mode of transport (courier, air, etc.).
     *
     * @return
     */
    public function mode()
    {
        return $this->belongsTo(Mode::class);
    }

    /**
     * A shipment belongs to a mode.
     *
     * @return
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * A shipment has one status.
     *
     * @return
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * A shipment has many alerts.
     *
     * @return
     */
    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    /**
     * A shipment is cancellable.
     *
     * @return bool
     */
    public function isCancellable()
    {
        // Prevent Primary Freight shipments from being cancelled that have been uploaded
        if ($this->carrier_id == 12 && $this->received_sent == 1) {
            return false;
        }

        if ($this->isActive() && ! $this->received || $this->status->code == 'saved') {
            return true;
        }

        return false;
    }

    /**
     * Shipment active - i.e. not cancelled or delivered.
     *
     * @return bool
     */
    public function isActive()
    {
        if (in_array($this->status->code, ['saved', 'cancelled', 'return_to_sender', 'available_for_pickup', 'failure', 'unknown']) || $this->delivered) {
            return false;
        }

        return true;
    }

    /**
     * Determines if a shipment is classified as domestic.
     *
     * @return bool
     */
    public function isDomestic()
    {
        if ($this->sender_country_code == $this->recipient_country_code) {
            return true;
        }

        return false;
    }

    /**
     * Returns a shipments EU status.
     *
     * @return bool
     */
    public function isWithinEu()
    {
        $sender = Country::where('country_code', $this->sender_country_code)->first()->eu;
        $recipient = Country::where('country_code', $this->recipient_country_code)->first()->eu;

        if ($sender && $recipient) {
            return true;
        }

        return false;
    }

    /**
     * Shipment has a commercial invoice.
     *
     * @return bool
     */
    public function hasCommercialInvoice()
    {
        if ($this->status->code == 'saved') {
            return false;
        }

        $viableDepartments = ['IFCEX', 'IFFAX'];

        if ((in_array($this->department->code, $viableDepartments) || in_array($this->recipient_country_code, ['GG', 'JE'])) && $this->status->code != 'cancelled') {
            return true;
        }

        return false;
    }

    /**
     * Get the delivery date.
     *
     * @param type $format
     * @return string
     */
    public function getDeliveryDate($format = 'd-m-Y H:i', $timezone = false)
    {
        if ($this->delivery_date) {
            if (! $timezone) {
                $timezone = $this->destination_timezone;
            }

            return $this->delivery_date->timezone($timezone)->format($format);
        }

        if ($this->isActive()) {
            return 'Awaiting Delivery';
        }

        return 'Unknown';
    }

    /**
     * @param type $format
     */
    public function getEstimatedDeliveryDate($format = 'd-m-Y', $timezone = false)
    {
        if (! $timezone) {
            $timezone = $this->destination_timezone;
        }

        if ($this->tracking) {
            if ($this->tracking->first()->estimated_delivery_date) {
                return $this->tracking->first()->estimated_delivery_date->timezone($timezone)->format($format);
            }
        }

        return 'Unknown';
    }

    /**
     * Toggles the on hold flag.
     *
     * @return null
     */
    public function toggleHold($userId)
    {
        if ($this->on_hold) {
            $this->on_hold = false;
        } else {
            $this->on_hold = true;
        }

        $this->save();
    }

    /**
     * Sets the shipment to delivered. Updates delivered flag, delivery date and signature.
     *
     * @param string $podSignature
     * @param string $deliveryDate
     *
     * @return  void
     */
    public function setDelivered($deliveryDate = null, $podSignature = 'Unknown', $userId = 0, $withTrackingEvent = false, $podImage = null)
    {
        // If the shipment has not been received, mark as received.
        if (! $this->received) {
            $this->setReceived($this->ship_date, $userId);
        }

        if ($this->delivered) {
            return false;
        }

        $this->delivered = true;
        $this->pod_signature = $podSignature;
        $this->pod_image = $podImage;
        $this->delivery_date = toCarbon($deliveryDate);
        $this->save();

        // Set the shipment status to "delivered"
        $this->setStatus('delivered', $userId, $deliveryDate, $withTrackingEvent);
    }

    /**
     * Sets the shipment to received - updates associated packages and inserts a tracking event for each package.
     *
     * @return null
     */
    public function setReceived($dateReceived = null, $userId = 0, $carrierScan = false, $updatePackages = true)
    {
        // Already received
        if ($this->received) {
            return false;
        }

        // Set to received
        $this->received = true;
        $this->save();

        // Update the shipment status to "received" (only if not on hold)
        if ($this->status->code == 'pre_transit') {

            // Update the ship date
            $this->ship_date = toCarbon($dateReceived);
            $this->save();

            $this->setStatus('received', $userId, $dateReceived, false);
        }

        if ($updatePackages) {
            // Update the package records to "received"
            foreach ($this->packages as $package) {
                $package->setReceived($dateReceived, $userId, $carrierScan);
            }
        }

        // Send shipment despatched email
        $this->alertGeneric('despatched');

        // Close collection request
        $this->closeCollectionRequest($dateReceived, $userId);

        // Create a delivery request (if required)
        $this->createDeliveryRequest();

        return true;
    }

    /**
     * Set the status of the shipment and adds a tracking event to show that
     * the status has changed.
     *
     * @param string $statusCode Status that the shipment will be changed to
     * @param int $userId User changing the shipment status
     * @param bool $withTrackingEvent
     *
     * @return null
     */
    public function setStatus($statusCode, $userId = 0, $datetime = false, $withTrackingEvent = true, $location = 'depot')
    {
        // Look up the status
        $status = Status::whereCode($statusCode)->first();

        // Only update if we have been given a valid status and the shipment is not currently set to this status
        // Also, dont update the status if currently RTS or Delivered - GMcNicholl 25-09-2018 17:54
        if ($status && $this->status_id != $status->id && ! in_array($this->status_id, ['6', '9'])) {

            // Change the status on the shipment record
            $this->status_id = $status->id;
            $this->save();

            // Add a tracking event for this status change
            if ($withTrackingEvent) {
                $this->addTracking($status->code, $datetime, $userId, false, $location);
            }

            // Send email notifications and update the alerts table
            $this->alertGeneric($statusCode);

            // Make sure collection request closed
            if ($status->id > 3 && $status->id < 8) {
                $this->closeCollectionRequest($datetime, $userId);
            }
        }
    }

    /**
     * Adds a tracking event to the shipment. Defaults the tracking location
     * to that of the shipment's associated depot. Requires a valid status as
     * a minimum requirement. If no message has been provided, a standard
     * message is retreived from the statuses table.
     *
     * @param string $status Status that the shipment will be changed to
     * @param int $userId User adding the tracking event
     * @param string $message Tracking message
     * @param string $datetime Date/time of the event
     * @param string $location Location to apply to the tracking event (depot, shipper or destination)
     *
     * @return mixed
     */
    public function addTracking($statusCode, $datetime = false, $userId = 0, $message = false, $location = 'depot')
    {
        // check the status if valid
        $status = Status::whereCode($statusCode)->first();

        if ($status) {
            if (! $message) {
                $message = $status->description;
            }

            if ($status->code == 'delivered') {
                $location = 'destination';
            }

            $location = $this->getTrackingEventLocation($location);

            $datetime = toCarbon($datetime);

            // Save or update the  record
            return Tracking::firstOrCreate(['message' => $message, 'status' => $statusCode, 'datetime' => $datetime, 'shipment_id' => $this->id])->update([
                'local_datetime' => $datetime,
                'carrier' => $this->carrier->name,
                'tracker_id' => 'trk_'.Str::random(26),
                'city' => $location['city'],
                'state' => $location['state'],
                'country_code' => $location['country_code'],
                'postcode' => $location['postcode'],
                'source' => 'ifs',
                'user_id' => $userId,
            ]);
        }

        return false;
    }

    /**
     * Get the location to apply to a tracking event.
     *
     * @param string $location
     * @return array
     */
    private function getTrackingEventLocation($location)
    {
        if (! $this->company || ! $this->depot) {
            return [
                'city' => 'Antrim',
                'state' => 'County Antrim',
                'country_code' => 'GB',
                'postcode' => 'BT41 2NQ',
                'timezone' => 'Europe/London',
            ];
        }

        switch ($location) {
            case 'shipper':
                return [
                    'city' => $this->company->city,
                    'state' => $this->company->state,
                    'country_code' => $this->company->country_code,
                    'postcode' => $this->company->postcode,
                    'timezone' => $this->company->localisation->time_zone,
                ];

            case 'destination':
                return [
                    'city' => $this->recipient_city,
                    'state' => $this->recipient_state,
                    'country_code' => $this->recipient_country_code,
                    'postcode' => $this->recipient_postcode,
                    'timezone' => getTimezone($this->recipient_country_code, $this->recipient_state, $this->recipient_city),
                ];

            default:
                return [
                    'city' => $this->depot->city,
                    'state' => $this->depot->state,
                    'country_code' => $this->depot->country_code,
                    'postcode' => $this->depot->postcode,
                    'timezone' => $this->depot->localisation->time_zone,
                ];
        }
    }

    /**
     * Close the collection request associated with the shipment.
     *
     * @param type $datetime
     * @param type $userId
     * @return bool
     */
    public function closeCollectionRequest($datetime, $userId = 2)
    {
        $transportJob = $this->transportJobs->where('type', 'c')->where('completed', 0)->first();

        if ($transportJob) {
            $transportJob->close($datetime, null, $userId);
        }
    }

    /**
     * Create a delivery request.
     */
    public function createDeliveryRequest()
    {
        // Don't create a delivery request if these conditions are met
        if ($this->carrier_id != 1 || $this->depot_id != 1 || ! $this->isActive() || $this->service->code == 'air' || $this->transportJobs->where('type', 'd')->count() > 0) {
            return false;
        }

        $cutOff = new Carbon('today 10:00', 'Europe/London');

        if (Carbon::now('Europe/London') > $cutOff) {
            $dateRequested = Carbon::now()->addWeekday();
        } else {
            $dateRequested = Carbon::now();
        }

        // Create Transport Job
        $transportJob = $this->transportJobs()->create([
            'number' => Sequence::whereCode('JOB')->lockForUpdate()->first()->getNextAvailable(),
            'reference' => $this->carrier_consignment_number,
            'pieces' => $this->pieces,
            'weight' => $this->weight,
            'goods_description' => $this->goods_description,
            'volumetric_weight' => $this->volumetric_weight,
            'instructions' => 'Deliver to: '.$this->recipient_name.', '.$this->recipient_address1.', '.$this->recipient_city.' '.$this->recipient_postcode.' '.$this->instructions,
            'scs_job_number' => $this->scs_job_number,
            'scs_company_code' => ($this->company->group_account) ? $this->company->group_account : $this->company->scs_code,
            'cash_on_delivery' => 0,
            'final_destination' => $this->recipient_city.','.$this->recipient_country,
            'type' => 'd',
            'from_type' => 'c',
            'from_company_name' => $this->company->company_name,
            'from_address1' => $this->depot->address1,
            'from_address2' => $this->depot->address2,
            'from_address3' => $this->depot->address3,
            'from_city' => $this->depot->city,
            'from_state' => $this->depot->state,
            'from_postcode' => $this->depot->postcode,
            'from_country_code' => $this->depot->country_code,
            'from_telephone' => $this->depot->telephone,
            'from_email' => $this->depot->email,
            'to_type' => $this->recipient_type,
            'to_name' => $this->recipient_name,
            'to_company_name' => $this->recipient_company_name,
            'to_address1' => $this->recipient_address1,
            'to_address2' => $this->recipient_address2,
            'to_address3' => $this->recipient_address3,
            'to_city' => $this->recipient_city,
            'to_state' => $this->recipient_state,
            'to_postcode' => $this->recipient_postcode,
            'to_country_code' => $this->recipient_country_code,
            'to_telephone' => $this->recipient_telephone,
            'to_email' => $this->recipient_email,
            'department_id' => $this->department_id,
            'depot_id' => $this->depot_id,
            'visible' => true,
            'date_requested' => $dateRequested,
        ]);

        $transportJob->setTransendRoute();
        $transportJob->setStatus('unmanifested');
    }

    /**
     * A shipment has many transport jobs.
     *
     * @return
     */
    public function transportJobs()
    {
        return $this->hasMany(TransportJob::class);
    }

    /**
     * Reverse out setDelivered actions. We should only ever have to reverse out manual POD against
     * IFS shipments, hence the restriction on the carrier ID.
     */
    public function undoSetDelivered()
    {
        if (! $this->delivered && $this->carrier_id != 1) {
            return false;
        }

        $this->delivered = false;
        $this->pod_signature = null;
        $this->delivery_date = null;
        $this->save();

        $this->tracking()->where('status', 'delivered')->delete();

        $this->setStatus('in_transit', 0, false, false);
    }

    /**
     * A shipment has many tracking events.
     *
     * @return
     */
    public function tracking()
    {
        return $this->hasMany(\App\Tracking::class)->orderBy('local_datetime', 'DESC')->orderBy('id', 'DESC');
    }

    /**
     * Cancel a shipment. Makes API call, sets status and cancels collection request.
     *
     * @param int $userId
     */
    public function setCancelled($userId = 0)
    {
        $this->setStatus('cancelled', $userId);

        // If sender postcode not "BT", mainland pickup may need cancelled
        if (! $this->originatesFromBtPostcode() && strtoupper($this->sender_country_code != 'US')) {
            Mail::to('courier@antrim.ifsgroup.com')->cc('courieruk@antrim.ifsgroup.com')->queue(new GenericError('Shipment Cancelled ('.$this->company->company_name.'/'.$this->consignment_number.')', 'Carrier pickup may need to be cancelled.'));
        }

        /*
         * Cancel collection/delivery requests
         */
        if ($this->transportJobs) {
            foreach ($this->transportJobs as $transportJob) {
                $transportJob->setCancelled();
            }
        }
    }

    /**
     * Determine if shipment originates from BT postcode.
     *
     * @return bool
     */
    public function originatesFromBtPostcode()
    {
        $prefix = strtoupper(substr(trim($this->sender_postcode), 0, 2));

        if ($prefix == 'BT' && strtoupper($this->sender_country_code) == 'GB') {
            return true;
        }

        return false;
    }

    /**
     * Undo a cancellation. Deletes tracking event, returns shipment back to previous status and
     * reinstates transport jobs.
     *
     * @param type $userId
     */
    public function undoCancel($userId = 0)
    {
        // Default status to return to
        $status = 'pre_transit';

        // Delete the cancellation tracking event
        $this->tracking()->where('status', 'cancelled')->delete();

        // Get the status prior to cancellation
        if ($this->tracking) {
            $lastEvent = $this->tracking->first();
            $status = $lastEvent->status;
        }

        // No tracking found, check if received and use this status
        if (! $this->tracking && $this->received) {
            $status = 'received';
        }

        // Reinstate transport jobs
        if ($this->transportJobs) {
            $collection = $this->transportJobs->where('type', 'c')->first();

            if ($collection) {
                // Not received, so reinstate collection request
                if (! $this->received) {
                    $collection->unmanifest();
                } else {
                    $collection->setStatus('completed');
                }
            }

            // Notify transport
            Mail::to('transport@antrim.ifsgroup.com')->cc('it@antrim.ifsgroup.com')->queue(new TransportJobReinstated($collection));

            // Reinstate delivery request if exists
            $delivery = $this->transportJobs->where('type', 'd')->first();

            if ($delivery) {
                $delivery->unmanifest();

                // Notify transport
                Mail::to('transport@antrim.ifsgroup.com')->cc('it@antrim.ifsgroup.com')->queue(new TransportJobReinstated($delivery));
            }
        }

        // Set the status on the shipment
        $this->setStatus($status, false, false, false);
    }

    /**
     * Returns the number of packages scanned i.e "1 of 2".
     *
     * @return string
     */
    public function getPackageScanCount()
    {
        $count = 0;
        foreach ($this->packages as $package) {
            if ($package->received) {
                $count++;
            }
        }

        return $count.' of '.$this->pieces;
    }

    /**
     * Create a collection request.
     */
    public function createCollectionRequest()
    {
        // Don't create a collection request if these conditions are met
        if ($this->depot_id != 1 || ! $this->isActive() || (! stristr($this->sender_postcode, 'BT') && strtoupper($this->sender_country_code) == 'GB') || $this->transportJobs->where('type', 'c')->count() > 0 || $this->company->testing == 1) {
            return false;
        }

        $transportJob = $this->transportJobs()->create([
            'number' => Sequence::whereCode('JOB')->lockForUpdate()->first()->getNextAvailable(),
            'reference' => $this->carrier_consignment_number,
            'pieces' => $this->pieces,
            'weight' => $this->weight,
            'goods_description' => $this->goods_description,
            'volumetric_weight' => $this->volumetric_weight,
            'instructions' => 'Collect from customer premises and bring back to '.$this->depot->name,
            'scs_job_number' => $this->scs_job_number,
            'scs_company_code' => ($this->company->group_account) ? $this->company->group_account : $this->company->scs_code,
            'cash_on_delivery' => 0,
            'final_destination' => $this->recipient_city.','.$this->recipient_country,
            'type' => 'c',
            'from_type' => $this->sender_type,
            'from_name' => $this->sender_name,
            'from_company_name' => $this->sender_company_name,
            'from_address1' => $this->sender_address1,
            'from_address2' => $this->sender_address2,
            'from_address3' => $this->sender_address3,
            'from_city' => $this->sender_city,
            'from_state' => $this->sender_state,
            'from_postcode' => $this->sender_postcode,
            'from_country_code' => $this->sender_country_code,
            'from_telephone' => $this->sender_telephone,
            'from_email' => $this->sender_email,
            'to_type' => 'c',
            'to_company_name' => $this->depot->name,
            'to_address1' => $this->depot->address1,
            'to_address2' => $this->depot->address2,
            'to_address3' => $this->depot->address3,
            'to_city' => $this->depot->city,
            'to_state' => $this->depot->state,
            'to_postcode' => $this->depot->postcode,
            'to_country_code' => $this->depot->country_code,
            'to_telephone' => $this->depot->telephone,
            'to_email' => $this->depot->email,
            'department_id' => $this->department_id,
            'depot_id' => $this->depot_id,
            'visible' => '1',
            'date_requested' => ($this->collection_date) ? $this->collection_date : $this->ship_date,
        ]);

        $transportJob->setTransendRoute();
        $transportJob->setStatus('unmanifested');
    }

    /**
     * Price shipment and if successful,
     * optionally update the Shipment record.
     *
     * @param type $toBeSaved
     * @return array Pricing breakdown offor costs and sales
     */
    public function price($toBeSaved = true, $debug = false)
    {

        // Build Packages Array
        $packages = [];
        foreach ($this->packages as $package) {
            $packages[] = $package->toArray();
        }

        // Build Shipment array for repricing
        $shipmentArray = $this->toArray();
        $shipmentArray['packages'] = $packages;

        // Reprice Shipment with new dims etc.
        $pricing = new Pricing();
        $pricing->debug = $debug;
        $price = $pricing->price($shipmentArray, $shipmentArray['service_id']);

        if ($price['errors'] == []) {
            $this->quoted = json_encode($price);
            $this->shipping_charge = $price['shipping_charge'];
            $this->shipping_cost = $price['shipping_cost'];
            $this->cost_currency = $price['cost_currency'];
            $this->sales_currency = $price['sales_currency'];
        }

        if ($toBeSaved) {
            $this->save();
        }

        return $price;
    }

    /**
     * Highlight a shipment that may require an operators attention. Currently highlights
     * pre-transit, ANT shipments that do not have a BT postcode. Can be extended for any
     * other requirements in the future.
     *
     * @return bool
     */
    public function isHighlighted()
    {
        if ($this->isActive() && $this->status->code == 'pre_transit' && $this->depot->code == 'ANT' && ! $this->originatesFromBtPostcode()) {
            return true;
        }

        return false;
    }

    /**
     * Highlight a shipment that may require an operators attention. Currently highlights
     * non MIA shipments that do not have a BT postcode. Can be extended for any other requirements
     * in the future.
     *
     * @return bool
     */
    public function formViewAvailable()
    {
        if (! $this->legacy && $this->status->code != 'saved' && $this->form_values) {
            return true;
        }

        return false;
    }

    /**
     * Calculate customs value in GBP.
     *
     * @return type
     */
    public function getGbpCustomsValue()
    {
        $currency = Currency::where('code', $this->customs_value_currency_code)->first();
        if ($currency) {
            return round($this->customs_value / $currency->rate, 2);
        }

        return $this->customs_value;
    }

    /**
     * Shipment has been booked with airline. Updates the carrier consignment number and adds a
     * tracking event to show shipment has been booked. Sends an email with airline tracking link.
     *
     * @param string $carrierConsignmentNumber
     * @param carbon $dateTimeBooked
     * @param int $userId
     */
    public function bookedWithAirline($carrierConsignmentNumber, $dateTimeBooked = false, $userId = 0)
    {
        // Not air freight or already updated
        if (strtoupper($this->service->scs_job_route) != 'XFF' || $this->carrier_consignment_number == $carrierConsignmentNumber) {
            return false;
        }

        // Update the carrier consignment number with airline consigment number
        $this->carrier_consignment_number = $carrierConsignmentNumber;
        $this->save();

        // Add a tracking event to show IFS booked with airline
        $this->addTracking('pre_transit', $dateTimeBooked, $userId, "Shipment booked with airline ($carrierConsignmentNumber)", 'depot');

        // Send email notifying customer of airline booking
        $this->sendBookedWithAirline();
    }

    /**
     * Add shipment to last manifest closed out.
     *
     * @return bool
     */
    public function addToLastManifest()
    {
        // Load the manifest profiles
        $manifestProfiles = ManifestProfile::all();

        // Loop through until we find a viable profile
        foreach ($manifestProfiles as $manifestProfile) :

            if ($manifestProfile->isShipmentViable($this->id)) {

                // Load the last manifest for this profile
                $lastManifest = Manifest::whereManifestProfileId($manifestProfile->id)->orderBy('id', 'desc')->first();

                if ($lastManifest) {
                    // Set the manifest ID on the shipment
                    $this->manifest_id = $lastManifest->id;
                    $this->save();

                    return $this->manifest_id;
                }
            }

        endforeach;

        return false;
    }

    /**
     * Determine if a shipment has an uploaded document.
     *
     * @param type $documentType
     * @return bool
     */
    public function hasUploadedDocument($documentType)
    {
        $document = $this->documents()->where('document_type', $documentType)->first();

        if ($document) {
            return true;
        }

        return false;
    }

    /**
     * A shipment has many documents.
     *
     * @return
     */
    public function documents()
    {
        return $this->belongsToMany(Document::class)->orderBy('id', 'DESC');
    }

    /**
     * Returns shipment PDF label document as separate base64 encode PNG files.
     *
     * @return array
     */
    public function getPngLabels()
    {
        $pngArray = [];

        if ($this->label) {

            // Save the base64 string as PDF document in the temp directory
            $pdfPath = storage_path('app/temp/'.$this->token.Str::random(6).'.pdf');
            $decodedFile = base64_decode($this->label->base64);
            file_put_contents($pdfPath, $decodedFile);

            // Convert the PDF to png images
            $pdf = new \Spatie\PdfToImage\Pdf($pdfPath);
            $numberOfPages = $pdf->getNumberOfPages();

            foreach (range(1, $numberOfPages) as $pageNumber) {
                $key = ($numberOfPages > $this->pieces && $pageNumber == 1) ? 'master' : 'package';
                $pngPath = storage_path('app/temp/'.Str::random(3).time().'.png');
                $pdf->setPage($pageNumber)->setCompressionQuality(100)->setResolution(300)->setOutputFormat('png')->saveImage($pngPath);

                if (file_exists($pngPath)) {
                    $img = file_get_contents($pngPath);
                    $pngArray[$key][] = base64_encode($img);
                    unlink($pngPath);
                }
            }
            unlink($pdfPath);
        }

        return $pngArray;
    }

    /**
     * Call the carrier tracking API and insert/update tracking events.
     */
    public function updateTracking()
    {
        $className = "\App\Tracking\\" . $this->carrier->name;

        $tracking = new $className($this);

        return $tracking->update();
    }
}
