<?php

namespace App\Models;

use App\Exports\DomesticZonesExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Database\Eloquent\Model;

class DomesticZone extends Model
{
    protected $guarded = ['id'];

    public function getZone($shipment, $model = "dx", $isReturn = false)
    {
        $zone = '';
        $postcode = '';
        $postCodeFound = false;

        // Decide which postcode to use to identify zone
        $zonePostcode = ($isReturn) ? $shipment['sender_postcode'] : $shipment['recipient_postcode'];
        $postcode = trim($zonePostcode) ?? '';

        // Remove all extraneous chars and compare against FedexUK DB
        $newPostCode = preg_replace('/\s+/', ' ', $postcode); // Replace multiple spaces
        $newPostCode = trim($newPostCode); // Remove Leading and trailing spaces
        $newPostCode = preg_replace('/[^A-Za-z0-9 ]/', '', $newPostCode); // Replace invalid characters
        if ($newPostCode == $postcode) {

            // Retrieve Cutoff time for the given PostCode/ Part PostCode
            $result = false;
            $l = strlen($newPostCode);
            while ($l > 0) {

                // Must have at least 1 Char
                $zone = self::where('postcode', '=', substr($newPostCode, 0, $l))
                    ->where('model', $model)
                    ->first();
                if (!empty($zone)) {
                    $postCodeFound = true;

                    return $zone->zone;
                } else {
                    $l = $l - 1;
                }
            }
        }

        return 'none';
    }

    public function download($model, $download = true)
    {
        $data = DomesticZone::select('postcode', 'zone', 'model', 'sla')
            ->where('model', $model)
            ->orderBy('model')
            ->orderBy('postcode')
            ->orderBy('zone')
            ->get()
            ->toArray();

        if (!empty($data)) {
            if ($download) {
                return Excel::download(
                    new DomesticZonesExport($data),
                    ucfirst($model) . '_Zones.csv'
                );
            } else {
                return $data;
            }
        }
    }
}
