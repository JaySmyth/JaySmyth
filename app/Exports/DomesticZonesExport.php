<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DomesticZonesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Headings.
     *
     * @return array
     */
    public function headings(): array
    {
        return array_keys($this->data[0]);
    }

    /**
     * @return type
     */
    public function collection()
    {
        $collection = collect();

        foreach ($this->data as $data) :
            $row = collect($data);
        $collection->push($row);
        endforeach;

        return $collection;
    }
}
