<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PurchaseInvoicesExport implements FromCollection, WithHeadings, ShouldAutoSize
{

    public function __construct($invoices)
    {
        $this->invoices = $invoices;
    }

    /**
     * Define column headings for the excel document.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Invoice',
            'Carrier',
            'Account',
            'Date',
            'Type',
            'I/E',
            'Total Cost',
            'Currency',
            'Received',
            'Queried',
            'Costs',
            'Copy Docs',
            'Status'
        ];
    }

    /**
     * Build the collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $collection = collect();

        foreach ($this->invoices as $invoice) :

            $row = collect([
                'Invoice' => $invoice->invoice_number,
                'Carrier' => $invoice->carrier->name,
                'Account' => $invoice->account_number,
                'Date' => $invoice->date->format('d-m-Y'),
                'Type' => verboseInvoiceType($invoice->type),
                'I/E' => verboseImportExport($invoice->import_export),
                'Total Cost' => $invoice->total,
                'Currency' => $invoice->currency_code,
                'Received' => $invoice->received,
                'Queried' => $invoice->queried,
                'Costs' => $invoice->costs,
                'Copy Docs' => $invoice->copy_docs,
                'Status' => $invoice->status_name
            ]);

            $collection->push($row);

        endforeach;

        return $collection;
    }

}
