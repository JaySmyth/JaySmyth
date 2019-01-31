<?php

use Illuminate\Database\Seeder;

class DomesticPurchaseInvoicesSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //disable foreign key check for this connection before running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::disableQueryLog();
        DB::connection('legacy')->disableQueryLog();
        DB::connection()->disableQueryLog();

        /*
         * Import Domestic PI from 1/1/15
         */
        $invoices = DB::connection('legacy')->select('SELECT * FROM FUKSuppInvH WHERE id > 392 ORDER BY id ASC');

        // Loop through each of the results
        foreach ($invoices as $invoice) {

            $checkExists = App\PurchaseInvoice::where('invoice_number', $invoice->inv_no)->where('carrier_id', 2)->first();

            if (!$checkExists) {

                // Build the array to create the invoice record
                $array = [
                    'invoice_number' => $invoice->inv_no,
                    'account_number' => $invoice->acct_no,
                    'total' => $invoice->tot_cost,
                    'total_taxable' => 0,
                    'total_non_taxable' => 0,
                    'vat' => $invoice->tot_vat,
                    'currency_code' => 'GBP',
                    'type' => 'F',
                    'import_export' => 'E',
                    'exported' => yesNoToBoolean($invoice->exported),
                    'received' => 1,
                    'queried' => 0,
                    'costs' => 0,
                    'copy_docs' => 0,
                    'copy_docs_email_sent' => 0,
                    'xml_generated' => yesNoToBoolean($invoice->exported),
                    'error' => null,
                    'status' => $invoice->status,
                    'carrier_id' => 2,
                    'date' => strtotime($invoice->inv_date),
                    'date_received' => strtotime($invoice->inv_date)
                ];

                // Save the  record
                $invoice = App\PurchaseInvoice::create($array);

                // Create the invoice lines            
                $lines = DB::connection('legacy')->select('SELECT * FROM FUKSuppInvD WHERE inv_no= :inv_no', ['inv_no' => $invoice->invoice_number]);

                foreach ($lines as $line) {

                    $carrierConsignmentNumber = '489' . $line->docketno;

                    $shipment = App\Shipment::whereCarrierConsignmentNumber($carrierConsignmentNumber)->whereCarrierId(2)->first();

                    $array = [
                        'shipment_reference' => ($shipment) ? $shipment->shipment_reference : null,
                        'carrier_consignment_number' => $carrierConsignmentNumber,
                        'carrier_tracking_number' => $carrierConsignmentNumber,
                        'pieces' => $line->pieces,
                        'weight' => $line->wght,
                        'weight_uom' => 'kg',
                        'billed_weight' => $line->billed_wght,
                        'length' => 0,
                        'width' => 0,
                        'height' => 0,
                        'dims_uom' => 'cm',
                        'volumetric_divisor' => 0,
                        'value' => 0,
                        'value_currency_code' => 'GBP',
                        'carrier_service' => $line->service_type,
                        'carrier_packaging_code' => $line->pkg_type,
                        'carrier_pay_code' => null,
                        'sender_name' => ($shipment) ? $shipment->sender_name : 'Unknown',
                        'sender_company_name' => ($shipment) ? $shipment->sender_company_name : null,
                        'sender_address1' => ($shipment) ? $shipment->sender_address1 : null,
                        'sender_address2' => ($shipment) ? $shipment->sender_address2 : null,
                        'sender_city' => ($shipment) ? $shipment->sender_city : null,
                        'sender_state' => ($shipment) ? $shipment->sender_state : null,
                        'sender_postcode' => ($shipment) ? $shipment->sender_postcode : null,
                        'sender_country_code' => ($shipment) ? $shipment->sender_country_code : 'GB',
                        'sender_account_number' => ($shipment) ? $shipment->bill_shipping_account : null,
                        'recipient_name' => ($shipment) ? $shipment->recipient_name : 'Unknown',
                        'recipient_company_name' => ($shipment) ? $shipment->recipient_company_name : null,
                        'recipient_address1' => ($shipment) ? $shipment->recipient_address1 : null,
                        'recipient_address2' => ($shipment) ? $shipment->recipient_address2 : null,
                        'recipient_city' => ($shipment) ? $shipment->recipient_city : null,
                        'recipient_state' => ($shipment) ? $shipment->recipient_state : null,
                        'recipient_postcode' => ($shipment) ? $shipment->recipient_postcode : null,
                        'recipient_country_code' => ($shipment) ? $shipment->recipient_country_code : 'GB',
                        'recipient_account_number' => null,
                        'pod_signature' => ($shipment) ? $shipment->pod_signature : 'Unknown',
                        'account_number1' => null,
                        'account_number2' => null,
                        'scs_job_number' => ($shipment) ? $shipment->scs_job_number : null,
                        'vat' => $line->vat,
                        'vat_rate' => $line->vat_rate,
                        'vat_code' => $line->vat_code,
                        'user_id' => $line->forced,
                        'purchase_invoice_id' => $invoice->id,
                        'ship_date' => strtotime($line->ship_date),
                        'delivery_date' => ($shipment) ? $shipment->delivery_date : null,
                        'shipment_id' => ($shipment) ? $shipment->id : null
                    ];

                    // Save the  record
                    $line = App\PurchaseInvoiceLine::create($array);

                    // Create the invoice charges            
                    $charges = DB::connection('legacy')->select('SELECT * FROM FUKSuppInvC WHERE inv_no = :inv_no AND docketno = :docketno', ['inv_no' => $invoice->invoice_number, 'docketno' => substr($line->carrier_consignment_number, 3)]);

                    foreach ($charges as $charge) {

                        $array = [
                            'code' => $this->getChargeCode($charge->chg_type),
                            'description' => $charge->surchDesc,
                            'amount' => $charge->billed_amt,
                            'currency_code' => 'GBP',
                            'exchange_rate' => 1,
                            'billed_amount' => $charge->billed_amt,
                            'billed_amount_currency_code' => $charge->billed_curr,
                            'purchase_invoice_id' => $invoice->id,
                            'purchase_invoice_line_id' => $line->id,
                            'carrier_charge_code_id' => $this->getChargeId($charge->chg_type)
                        ];

                        // Save the  record
                        $charge = App\PurchaseInvoiceCharge::create($array);
                    }
                }
            }
        }
    }

    private function getChargeCode($code)
    {
        switch ($code) {
            case 'FRT':
                return '050';

            case 'OOA':
                return '043';
        }
    }

    private function getChargeId($code)
    {
        switch ($code) {
            case 'FRT':
                return 50;

            case 'OOA':
                return 43;
        }
    }

}
