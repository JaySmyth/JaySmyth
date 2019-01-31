<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SurchargesExport implements FromCollection, WithHeadings, ShouldAutoSize
{

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Headings 
     * 
     * @return array
     */
    public function headings(): array
    {
        return array_keys($this->data->first()->toArray());
    }

    /**
     * 
     * @return type
     */
    public function collection()
    {
        return $this->data;
    }

}
