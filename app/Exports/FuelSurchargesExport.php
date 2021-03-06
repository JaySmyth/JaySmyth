<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FuelSurchargesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct($shipments)
    {
        $this->shipments = $shipments;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [

        ];
    }

    public function collection()
    {
        $collection = collect();

        foreach ($this->shipments as $shipment) :

            $row = collect([

            ]);

        $collection->push($row);

        endforeach;

        return $collection;
    }
}
