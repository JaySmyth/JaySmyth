<?php

namespace App\CarrierAPI;

use TCPDI;

abstract class CarrierLabel
{
    /*
      |--------------------------------------------------------------------------
      | CarrierLabel Class (abstract)
      |--------------------------------------------------------------------------
      |
      | Extended by carrier label.
      |
     */

    protected $shipment;
    protected $data;
    protected $serviceCode;
    protected $routeId;
    protected $splitServiceBox;
    protected $pdf;
    protected $font = 'helvetica';

    /**
     * @param  type  $shipment
     * @param  type  $data
     */
    public function __construct($shipment = null, $serviceCode = null, $data = null, $routeId = 1, $splitServiceBox = false)
    {
        $this->shipment = $shipment;
        $this->data = $data;
        $this->serviceCode = $serviceCode;
        $this->routeId = $routeId;
        $this->splitServiceBox = $splitServiceBox;

        // New instance of TCPDI
        $this->pdf = new TCPDI();

        // remove the default head/footers
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);

        // remove all margins
        $this->pdf->setMargins(0, 0, 0);
        $this->pdf->SetAutoPageBreak(false, 0);

        // set image scaling
        $this->pdf->setImageScale(1);
    }

    /**
     * Create PDF label.
     */
    abstract public function create();

    /**
     * Import a page.
     *
     * @param  type  $pageNumber
     */
    protected function importPageFromTemplate($pageNumber, $x = 1, $y = 1, $width = null, $height = null)
    {
        $tpl = $this->pdf->importPage($pageNumber);

        if (is_numeric($width) && is_numeric($height)) {
            $originalPdfSize = [
                'w' => $width,
                'h' => $height
            ];
        } else {
            $originalPdfSize = $this->pdf->getTemplateSize($tpl);
        }

        // Add a blank page
        $this->addPage();

        $this->pdf->useTemplate($tpl, $x, $y, $originalPdfSize['w'], $originalPdfSize['h'], false);
    }

    /**
     * Add a 6x4 page to the PDF.
     */
    protected function addPage()
    {
        $this->pdf->AddPage('P', ['102', '153']);
    }

    /**
     * Insert a base64 image to the PDF.
     *
     * @param  string  $base64Image
     * @param  array  $options
     */
    protected function addImage($base64Image, $options)
    {
        $this->pdf->StartTransform();
        $this->pdf->Rotate(-90);
        $this->pdf->Image('@'.base64_decode($base64Image), $options[0], $options[1], $options[2], $options[3]);
        $this->pdf->StopTransform();
    }


    /**
     * Return the generated PDF.
     *
     * @param  type  $output
     *
     * @return type
     */
    protected function output($output = 'S')
    {
        if ($output != 'S') {
            return $this->pdf->Output('label.pdf', $output);
        }

        return base64_encode($this->pdf->Output('label.pdf', 'S'));
    }

    /**
     * Return TCPDF 1D barcode styling array.
     *
     * @param  type  $fontsize
     *
     * @return type array
     */
    protected function getBarcodeStyle($text = true, $fontsize = 10)
    {
        return [
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 0,
            'vpadding' => 0,
            'fgcolor' => [0, 0, 0],
            'bgcolor' => false,
            'text' => $text,
            'font' => 'helvetica',
            'fontsize' => $fontsize,
            'stretchtext' => 4,
        ];
    }

    protected function addServiceBox($x, $y, $w, $h, $service)
    {
        // Create IFS Service Box
        $this->pdf->SetXY($x - 2, $y);
        $this->pdf->SetFont('', 'BI', 28);

        if ($this->routeId == 1) {
            $this->buildServicebox($x, $y, $w, $h, $service, 'IFS*', 0, 255);
        } else {
            $this->buildServicebox($x, $y, $w, $h, $service, 'IFS', 255, 0);
        }
    }

    protected function buildServicebox($x, $y, $w, $h, $service, $heading, $fillColour, $textColour)
    {
        // Initialize Font and Fill colours
        $this->pdf->SetTextColor($textColour, $textColour, $textColour);
        $this->pdf->SetFillColor($fillColour, $fillColour, $fillColour);

        // Create Box with Heading
        $this->pdf->SetLineWidth(1);
        $this->pdf->Cell($w, $h, '', 1, 0, 0, true);
        $this->pdf->Text($x - 2, $y - 1, $heading);

        // IFS Company Name
        $this->pdf->SetFont('', 'B', 7);
        $this->pdf->Text($x - 2, $y += 9, 'Courier Express');

        // If Required, reverse colours for displaying service code
        if ($this->splitServiceBox) {
            $this->pdf->SetTextColor($fillColour, $fillColour, $fillColour);
            $this->pdf->SetFillColor($textColour, $textColour, $textColour);
        }

        // Display and center service code
        $this->pdf->SetXY($x - 1, $y + 3);
        $this->pdf->SetFont('', 'BI', 16);

        $this->pdf->cell($w - 2, 5, strtoupper($this->serviceCode), 0, 1, 'C', true);
    }

    protected function addLongServiceBox($x, $y, $w, $h, $service)
    {
        // Create IFS Service Box
        $this->pdf->SetFillColor(255, 255, 255);
        $this->pdf->SetLineWidth(1);
        $this->pdf->SetXY($x, $y);
        $this->pdf->Cell($w, $h, '', 1, 0, 0, true);

        // Display and center Service code
        $this->pdf->SetXY($x - 1, $y + .5);
        $this->pdf->SetFont('', 'B', 7);
        $this->pdf->cell($w, 3, 'IFS Courier Express', 0, 1, 'C', false);

        // Add Contents to box
        // Display and center Service code
        $this->pdf->SetXY($x - 1, $y + 2);
        $this->pdf->SetFont('', 'BI', 16);
        $this->pdf->cell($w, 7, 'IFS - '.strtoupper($this->serviceCode), 0, 1, 'C', false);
    }

    protected function removeLogo($x, $y, $w, $h)
    {
        // Remove Logo
        $this->pdf->SetXY($x, $y);
        $this->pdf->Cell($w, $h, '', 0, 0, 0, true);
    }
}
