<?php

namespace App\Exports;

use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ShipmentImportExampleExport implements FromCollection, ShouldAutoSize
{
    protected $importConfig;
    protected $faker;
    protected $limit = 3;

    public function __construct($importConfig, $faker)
    {
        $this->importConfig = $importConfig;
        $this->faker = $faker;
    }

    public function collection()
    {
        $headings = [];
        $rows = [];
        $rowNumber = 1;
        $columns = $this->importConfig->getColumns();

        $productCode = \App\Models\Commodity::select('product_code')->whereCompanyId($this->importConfig->company_id)->where('product_code', '>', '')->orderBy('id', 'DESC')->first();

        $previousShipments = \App\Models\Shipment::whereCompanyId($this->importConfig->company_id)->whereStatusId(6)->inRandomOrder()->limit($this->limit)->get();

        if ($previousShipments->count() != $this->limit) {
            $previousShipments = \App\Models\Shipment::whereStatusId(6)->orderBy('id', 'desc')->limit($this->limit)->get();
        }

        foreach ($previousShipments as $previousShipment) {
            $recipient_company_name = $this->faker->company;
            $recipient_name = $this->faker->firstName.' '.$this->faker->lastName;
            $recipient_address1 = $this->faker->buildingNumber.' '.$this->faker->streetName;
            $recipient_email = $this->faker->email;
            $recipient_telephone = '028 94464211';
            $shipment_reference = strtoupper("TEST$rowNumber".Str::random(2));
            $goods_description = $this->importConfig->default_goods_description;
            $service_code = strtoupper($previousShipment->service->code);
            $length = rand(20, 80);
            $width = rand(20, 80);
            $height = rand(20, 80);
            $product_code = ($productCode) ? $productCode->product_code : Str::random(6);
            $product_quantity = rand(1, 5);
            $ignore = '';

            foreach ($columns as $column) {
                if ($rowNumber == 1 && $this->importConfig->start_row != 1) {
                    $headings[0][] = $column;
                }

                $rows[$rowNumber][] = strtoupper(isset($$column) ? $$column : $previousShipment->$column);
            }

            $rowNumber++;
        }

        if (count($headings) > 0) {
            $rows = $headings + $rows;
        }

        return collect($rows);
    }
}
