<?php

namespace App\CarrierAPI\TNT;

use TCPDI;

class TNTManifest
{

    private $shipments;
    private $pdf;
    private $font = 'helvetica'; // tcpdf doesnt support arial font out of the box

    /**
     *
     * @param type $shipments
     */

    function __construct($shipments)
    {
        $this->shipments = $shipments;

        // New instance of TCPDI
        $this->pdf = new TCPDI();

        // remove the default head/footers
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
    }

    /**
     * Create a TNT manifest.
     *
     * @return string   base64 PDF
     */
    public function create()
    {
        $y = 10;
        $x = 10;
        $line = 4;
        $consignmentsPerPage = 2;

        $i = 0;
        foreach ($this->shipments as $shipment) {

            if ($i % $consignmentsPerPage == 0) {
                $y = 10;
                $this->pdf->AddPage(); // Add a new page to the PDF
                $this->pdf->SetFont($this->font, 'BU', 11);
                $this->pdf->Text(50, $y, 'COLLECTION MANIFEST (DETAIL) - OTHERS (Sender Pays)');
                $this->pdf->SetFont($this->font, 'B', 30);
                $this->pdf->Text(10, $y - 2, 'TNT');
                $this->pdf->SetFont($this->font, '', 8);
                $this->pdf->Text(90, $y + 7, 'TNT Express');
                $this->pdf->Text(90, $y + 13, 'Pickup Id :');
                $this->pdf->Text(170, $y + 13, 'Printed on : ' . date("d/m/Y"));

                $y += 18;
                $this->pdf->Line(0, $y, 250, $y); //horizontal
                $y = $y + 2;
            }

            $this->pdf->SetFont($this->font, '', 8);
            $this->pdf->Text($x, $y = $y + $line, 'Sender Account');
            $this->pdf->Text($x + 25, $y, ': ' . $shipment->bill_shipping_account);
            $this->pdf->Text($x, $y = $y + $line, 'Sender Name');
            $this->pdf->Text($x + 25, $y, ': ' . $shipment->sender_company_name);
            $this->pdf->Text($x, $y = $y + $line, '& Address');
            $this->pdf->Text($x + 25, $y, ': ' . $shipment->sender_address1 . ', ' . $shipment->sender_address2 . ', ' . $shipment->sender_address3 . ', ' . $shipment->sender_city . ', ' . $shipment->sender_state . ', ' . $shipment->sender_postcode . ', ' . $shipment->sender_country_code);

            $y = $y + $line;

            //$this->pdf->Line(0, $y, 250, $y); //horizontal
            //$y = $y + $line;
            $this->pdf->Text(160, $y - 4, 'Shipment Date : ' . $shipment->ship_date->format('d-m-Y'));
            $this->pdf->Text(82, $y + 2, 'Sending Depot         Receiving Depot');
            $this->pdf->SetFont($this->font, 'BU', 8);
            $this->pdf->Text(140, $y + 2, 'Special Instructions');
            $this->pdf->SetFont($this->font, '', 8);
            $this->pdf->Text(140, $y + 6, $shipment->special_instructions);
            $this->pdf->Text(140, $y + 10, 'Sender Pays');

            /*
             * 1D barcode.
             */

            $style = [
                'position' => '',
                'align' => 'C',
                'stretch' => false,
                'fitwidth' => true,
                'cellfitalign' => '',
                'border' => false,
                'hpadding' => 0,
                'vpadding' => 0,
                'fgcolor' => array(0, 0, 0),
                'bgcolor' => false,
                'text' => true,
                'font' => 'helvetica',
                'fontsize' => 10,
                'stretchtext' => 4
            ];

            $this->pdf->write1DBarcode($shipment->carrier_consignment_number, 'C128', 15, $y += 3, '', 20, 0.4, $style, 'N');

            $y += 18;
            $this->pdf->SetFont($this->font, '', 8);
            $this->pdf->Text($x, $y = $y + $line, 'Sender Contact');
            $this->pdf->Text($x + 25, $y, ': ' . $shipment->sender_name . ' Tel:' . $shipment->sender_telephone . '  Sender Ref: ' . $shipment->shipment_reference . ' Receiver Vat Nr: Receiver Acc Numer:');
            $this->pdf->Text($x, $y = $y + $line, 'Receiver Name');
            $this->pdf->Text($x + 25, $y, ': ' . 'IFS Global Logistics, IFS Logistics Park, Seven Mile Straight Receiver Contact: Courier Department');
            $this->pdf->Text($x, $y = $y + $line, '& Address');
            $this->pdf->Text($x + 25, $y, ': ' . 'Antrim, BT41 4QE, United Kingdom');
            $this->pdf->Text($x, $y = $y + $line, 'Receiver Tel');
            $this->pdf->Text($x + 25, $y, ': ' . '028 94464211');
            $this->pdf->Text($x, $y = $y + $line, 'Collection');
            $this->pdf->Text($x + 25, $y, ': ' . '');
            $this->pdf->Text($x, $y = $y + $line, '& Address');
            $this->pdf->Text($x + 25, $y, ': ' . '');
            $this->pdf->Text($x, $y = $y + $line, 'Delivery');
            $this->pdf->Text($x + 25, $y, ': ' . $shipment->recipient_company_name . ', ' . $shipment->recipient_address1 . ', ' . $shipment->recipient_address2 . ', ' . $shipment->recipient_address3);
            $this->pdf->Text($x, $y = $y + $line, '& Address');
            $this->pdf->Text($x + 25, $y, ': ' . $shipment->recipient_city . ', ' . $shipment->recipient_state . ', ' . $shipment->recipient_postcode . ', ' . $shipment->recipient_country_code);
            $this->pdf->Text($x, $y = $y + $line, 'No Pieces : ' . $shipment->pieces);
            $this->pdf->Text($x + 25, $y, 'Weight : ' . $shipment->weight . $shipment->weight_uom);
            $this->pdf->Text($x + 55, $y, 'Insurance Value : GBP');
            $this->pdf->Text($x + 100, $y, 'Invoice Value : GBP');
            $this->pdf->Text($x + 135, $y, 'Total Consignment Volume : ' . $shipment->volumetric_weight);
            $this->pdf->Text($x, $y = $y + 8, 'Description');
            $this->pdf->Text($x + 80, $y, 'Dimensions (L x W x H)');

            foreach ($shipment->packages as $package) {
                $this->pdf->Text($x, $y = $y + $line, $shipment->goods_description);
                $this->pdf->Text($x + 80, $y, $package->length . 'cm x ' . $package->width . 'cm x ' . $package->height . 'cm');
            }

            $y += 8;
            $this->pdf->Line(0, $y, 250, $y); //horizontal           
            $i++;
        }

        $path = storage_path('app/temp/tnt_manifest_' . time() . '.pdf');

        // Output the PDF
        $this->pdf->Output($path, 'F');

        // Return the path to the tempfile
        return $path;
    }

}
