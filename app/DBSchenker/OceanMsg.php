<?php

namespace App\DBSchenker;

use App\Models\Unlocode;
use App\Multifreight\DocAdds;
use App\Multifreight\JobLine;
use App\Multifreight\RecCont;
use Illuminate\Support\Facades\Validator;

class OceanMsg
{
    public $msg;
    public $errors = [];

    /**
     * Sets the file type and table.
     *
     * @param  type  $filename
     *
     * @return bool
     */
    public function buildMsg($jobHdr)
    {
        $msg = [];
        $jobLines = JobLine::where('job_id', $jobHdr->job_id)->get();
        if ($jobLines) {
            $containers = RecCont::where('rec_id', $jobHdr->job_id)->orderBy('line_no')->get();
            $consor = DocAdds::where('job_id', $jobHdr->job_id)->where('address_type', 'CONSOR')->where('line_no', '1')->first();
            $consee = DocAdds::where('job_id', $jobHdr->job_id)->where('address_type', 'CONSEE')->where('line_no', '1')->first();
            $notify = DocAdds::where('job_id', $jobHdr->job_id)->where('address_type', 'NOTIFY')->where('line_no', '1')->first();
            $carrier = DocAdds::where('job_id', $jobHdr->job_id)->where('address_type', 'CARRIER')->where('line_no', '1')->first();
        }

        // Admin Record
        $msg['admin'] = $this->buildAdmin($jobHdr, $jobLines, $notify->keyname, $msg);

        // Header Record
        $msg['header'] = $this->buildHeader($jobHdr->bill_of_lading, $jobHdr->bol_orig);

        // Reference records
        $msg['reference'] = $this->buildReferences($jobHdr);

        // Transport Header Record
        $msg['theader'] = $this->buildTheader($jobHdr, $carrier);

        // Transport Dates record
        $rec = $this->buildTransportDate('132', $jobHdr->estimated_arrival_date);
        if ($rec > '') {
            $msg['tdates']['tdate'][] = $rec;
        }
        $rec = $this->buildTransportDate('133', $jobHdr->estimated_dept_date);
        if ($rec > '') {
            $msg['tdates']['tdate'][] = $rec;
        }
        $rec = $this->buildTransportDate('136', $jobHdr->actual_dept_date);
        if ($rec > '') {
            $msg['tdates']['tdate'][] = $rec;
        }
        if ($jobHdr->port_of_loading > '') {
            $unlocode = new Unlocode();
            $locode = $unlocode->getLoCode($consor->country_code, $jobHdr->port_of_loading, $jobHdr->pol_description);
            $rec = $this->buildTransportLocns('5', $locode);
            if ($rec != '') {
                $msg['tlocations']['tlocation'][] = $rec;
            }
        }
        /*
        if ($jobHdr->pol_description > '') {
            $rec = $this->buildTransportLocns('9', 'GB' . $jobHdr->pol_description);
            if ($rec != '') {
                $msg['tlocations']['tlocation'][] = $rec;
            }
        }
        */
        if ($jobHdr->final_dest > '' || $jobHdr->final_desc > '') {
            $unlocode = new Unlocode();
            $locode = $unlocode->getLoCode($consee->country_code, $jobHdr->final_dest, $jobHdr->final_desc);
            $rec = $this->buildTransportLocns('7', $locode);
            if ($rec != '') {
                $msg['tlocations']['tlocation'][] = $rec;
            }
        }
        if ($jobHdr->port_of_discharge > '' || $jobHdr->pod_description > '') {
            $unlocode = new Unlocode();
            $locode = $unlocode->getLoCode($consee->country_code, $jobHdr->port_of_discharge, $jobHdr->pod_description);
            $rec = $this->buildTransportLocns('12', $locode);
            if ($rec != '') {
                $msg['tlocations']['tlocation'][] = $rec;
            }
        }
        // Address Records
        $rec = $this->buildAddress('CN', $consee);
        if ($rec != '') {
            $msg['taddress']['partnerAddr'][] = $rec;
        }
        $rec = $this->buildAddress('CZ', $consor);
        if ($rec != '') {
            $msg['taddress']['partnerAddr'][] = $rec;
        }
        $rec = $this->buildAddress('BA', $consee);
        if ($rec != '') {
            $msg['taddress']['partnerAddr'][] = $rec;
        }

        // Communications Contact Record - Ignored

        // Goods Description records
        foreach ($jobLines as $jobLine) {
            $rec = $this->buildLine($jobLine);
            if ($rec != '') {
                $msg['goodsDetails']['goods'][] = $rec;
            }
        }

        // Package Details record
        $msg['package'] = $this->buildPackage($jobHdr);

        // Equipment Unit record
        // if (strtoupper($jobHdr->load_type) == 'FCL') {
        $msg['equipmentUnit'] = $this->buildEquipmentUnit($jobHdr, $containers);
        //}

        // Delivery Terms record
        $msg['dterms'] = $this->buildDterms($jobHdr->terms_code, $jobHdr->terms_location);

        $this->msg = $msg;
    }

    protected function buildAdmin($jobHdr, $jobLines, $keyName)
    {
        $msg = [];
        $msg['originBranchId'] = 'SJCBFS';
        $msg['blMessageId'] = $jobHdr->job_disp;
        $msg['houseBlRunningNumber'] = 1;
        $msg['sttNumber'] = $this->getSTTNumber();
        $msg['exportImport'] = 'E';
        $msg['etdDate'] = date('Ymd', strtotime($jobHdr->estimated_dept_date));
        $msg['etdTime'] = '0000';
        $msg['timeZone'] = 'GMT';
        $msg['etaDate'] = date('Ymd', strtotime($jobHdr->delivered_date));
        $msg['etaTime'] = '0000';
        $msg['creationDate'] = date('Ymd');
        $msg['creationTime'] = date('His');
        $msg['houseBlUsingCode'] = 'O';
        $msg['natureOfGoods'] = $this->natureOfGoods($jobLines);
        $msg['finalDestinationBranch'] = $keyName.'SSF';
        $msg['status'] = 'BL1.4.1';
        $msg['loadType'] = $jobHdr->load_type;

        return $msg;
    }

    protected function getSTTNumber()
    {
        $countryCode = '826';
        $partnerId = '6';
        $sequence = nextAvailable('SCHENKERSTT');
        $base = $countryCode.$partnerId.sprintf('%9d', $sequence);
        $sum1 = $base[1] + $base[3] + $base[5] + $base[7] + $base[9] + $base[11];
        $sum2 = $base[0] + $base[2] + $base[4] + $base[6] + $base[8] + $base[10] + $base[12];
        $sum = $sum1 + ($sum2 * 3);
        $checkDigit = 10 - $sum % 10;

        return $base.$checkDigit;
    }

    protected function natureOfGoods($jobLines)
    {
        $nature = 'GEN';
        foreach ($jobLines as $jobLine) {
            if ($jobLine->haz_pieces > 0) {
                $nature = 'HAZ';
            }
        }

        return $nature;
    }

    protected function buildHeader($bolNumber, $bolCopies)
    {
        $msg['documentCode'] = '703';
        $msg['messageId'] = $bolNumber;
        $msg['messageFunction'] = '9';       // 9 -  Create, 5 - Replace (Update)
        $msg['documentPlace'] = 'GBANT';     //  GBBEL $jobHdr->receipt;
        $msg['documentDate'] = date('Ymd');
        $msg['documentDateFormat'] = '102';
        $msg['numberOfOriginals'] = intval($bolCopies);
        $msg['blNumber'] = $bolNumber;

        return $msg;
    }

    protected function buildReferences($jobHdr)
    {
        $msg = [];
        $ref = $this->buildReference('AEG', $jobHdr->cust_ref);
        if ($ref > '') {
            $msg['reference'][] = $ref;
        }

        $ref = $this->buildReference('FF', $jobHdr->job_disp);
        if ($ref > '') {
            $msg['reference'][] = $ref;
        }

        return $msg;
    }

    protected function buildReference($qualifier = 'AEG', $custRef = '')
    {
        if ($custRef > "") {
            return [
                'referenceQualifier' => $qualifier,
                'referenceNumber' => $custRef
            ];
        }

        return '';
    }

    protected function buildTheader($jobHdr, $carrier)
    {
        $msg['transportStageQualifier'] = '20';
        $msg['conveyanceReference'] = $jobHdr->vessel_code;
        $msg['modeOfTransport'] = '10';
        $msg['typeOfTransport'] = '13';
        $msg['carrierId'] = $this->getCarrierSCACCode($carrier);
        $msg['carrierName'] = $carrier->name;
        $msg['transportId'] = $jobHdr->vessel_name;

        return $msg;
    }

    protected function getCarrierSCACCode($carrier)
    {
        return $carrier->keyname;
    }

    protected function buildTransportDate($qualifier, $date)
    {
        /*
            132 - Est Date of Arrival
            133 - Est Date of Departure
            136 - Departure Date
        */
        if ($date > "") {
            return [
                'transportQualifier' => $qualifier,
                'transportDate' => date('Ymd', strtotime($date)),
                'transportDateFormat' => '102',
            ];
        }

        return '';
    }

    protected function buildTransportLocns($qualifier, $locn)
    {
        // Transport Location record
        /*
            5 = Port of Loading
            7 = Place of Delivery (Final Destination)
            9 = Place of Loading
            12 = Port of Discharge
        */
        if (! $locn) {
            return null;
        }

        return [
            'placeQualifier' => $qualifier,
            'placeId' => $locn,
        ];
    }

    protected function buildAddress($qualifier, $address = null)
    {
        if (! $address) {
            return null;
        }
        switch ($qualifier) {
            case 'BA':
            case 'CN':
            case 'CZ':
                return [
                    'addressQualifier' => $qualifier,
                    'addressId' => $address->id,
                    'addressStructure' => '2',
                    'name' => $address->name ?? '',
                    'streetAndNumber1' => $address->address_1 ?? '',
                    'cityName' => $address->town ?? '',
                    'postCode' => $address->postcode ?? '',
                    'countryCode' => $address->country_code ?? '',
                    'comAddressId' => $qualifier ?? '',
                    'contactFunction' => 'IC',
                    'contactEmployeeName' => empty($address->contact_name) ? 'Not Known' : $address->contact_name,
                    'communicationId' => 'TE',
                    'communicationData' => empty($address->telephone) ? 'Not Known' : $address->telephone,
                ];
            default:
                return null;
        }
    }

    protected function buildLine($jobLine)
    {
        $line = [
            'itemNumber' => $jobLine->line_no,
            'numberOfPackages' => $jobLine->pieces,
            'measurement' => null,
        ];

        // Add Actual Weight
        $measurement[] = [
            'measureQualifier' => "WT",
            'measureUnit' => 'KGM',
            'measureValue' => $jobLine->entered_wgt,
        ];

        // Add Volumetric Weight
        $measurement[] = [
            'measureQualifier' => "WT",
            'measureUnit' => 'MTQ',
            'measureValue' => $jobLine->vol_wgt,
        ];

        $line['measurement']['measure'] = $measurement;

        return $line;
    }

    protected function buildPackage($jobHdr)
    {
        return [
            'totalNumberPackages' => $jobHdr->pieces,
            'totalGrossWeight' => $jobHdr->chg_weight,
            'weightMeasureQualifier' => 'KGM',
            'totalCube' => $jobHdr->cube,
            'cubeMeasureQualifier' => 'MTQ',
        ];
    }

    protected function buildEquipmentUnit($jobHdr, $containers)
    {
        $equipUnit = [];
        foreach ($containers as $container) {
            $data = [
                'equipmentQualifier' => 'CN',
                'equipmentIdNumber' => $container->container_number,
                'sizeAndTypeText' => $container->container_code."' Container",
                'supplier' => '2',
                'movementPlan' => $jobHdr->load_type.'/'.$jobHdr->load_type,
                'measurement' => null,
                'seal' => $container->seal_number,

            ];
            $data['measurement'] = $this->buildContainerMeasurement($jobHdr);

            $equipUnit[] = $data;
        }

        return $equipUnit;
    }

    /*
        protected function validateMsg($msg)
        {
            $errors = [];
            $schemaId = 'http://shipg1.ifsgroup.com/dbs_ocean.json';

            // Convert to an object - cannot accept json string
            $msg = json_decode(json_encode($msg));

            // Create a new validator
            $validator = new Validator();
            $validator->resolver()->registerFile('http://shipg1.ifsgroup.com/schema/dbs_ocean.json', '/var/www/production/public/schema/dbs_ocean.json');

            // Uri validation
            $result = $validator->validate($msg, 'http://shipg1.ifsgroup.com/schema/dbs_ocean.json');
            if (! $result->isValid()) {
                $errors = (new ErrorFormatter())->format($result->error());
            }

            return $errors;
        }
        */

    protected function buildContainerMeasurement($container)
    {
        $data['measure'][] = [
            'measureQualifier' => "WT",
            'movementDimensionCode' => "U",
            'measureUnit' => "KGM",
            'measureValue' => $container->kgs_weight,

        ];
        $data['measure'][] = [
            'measureQualifier' => "VOL",
            'movementDimensionCode' => "U",
            'measureUnit' => "MTQ",
            'measureValue' => $container->cube,
        ];

        return $data;
    }

    protected function buildDterms($terms, $locn)
    {
        $msg['termsOfDeliveryCode'] = $terms;
        $msg['termsOfDeliveryCodeList'] = $terms;
        $msg['termsLocation'] = $terms.' '.$locn;

        return $msg;
    }

    public function validateMsg()
    {
        $this->validateAdmin();
        $this->validateHeader();
        $this->validateReference();
        $this->validateTHeader();
        $this->validateTDates();
        $this->validateTLocations();
        $this->validateTAddress();
        $this->validateGoods();
        $this->validatePackage();
        $this->validateEquipmentUnit();
        $this->validateDTerms();

        return $this->errors;
    }

    public function validateAdmin()
    {
        $section = $this->msg['admin'];
        $validator = Validator::make($section, [
            'originBranchId' => 'required|string|size:6|alpha_num',
            'blMessageId' => 'required|string|max:20|alpha_num',
            'houseBlRunningNumber' => 'required|integer|min:1|max:999',
            'sttNumber' => 'required|string|max:20|alpha_num',
            'exportImport' => 'required|in:I,E,',
            'etdDate' => 'required|date_format:Ymd',
            'etdTime' => 'required|digits:4',
            'timeZone' => 'required|in:GMT',
            'etaDate' => 'required|date_format:Ymd',
            'etaTime' => 'required|date_format:Hi',
            'creationDate' => 'required|date_format:Ymd',
            'creationTime' => 'required|date_format:His',
            'houseBlUsingCode' => 'required|in:O',
            'natureOfGoods' => 'required|in:GEN',
            'finalDestinationBranch' => 'required|string|min:6|max:10|alpha_num',
            'status' => 'required|min:5|max:10',
            'loadType' => 'nullable|in:LCL,FCL'
        ]);

        /*
        $finalDestCode = $this->msg['admin']['finalDestinationBranch'];
        if (substr($finalDestCode,-3) == 'SSF') {
            $finalDestCode = substr($finalDestCode,0,strlen($finalDestCode)-3);
        }

        $validator->errors()->add(
            'field', 'Something is wrong with this field!'
        );
        */

        if ($validator->fails()) {
            $this->mergeErrors('Admin', $section, $validator->errors());
        }
    }

    protected function mergeErrors($heading, $section, $errorBag)
    {
        $errors = $errorBag->toArray();
        foreach ($errors as $key => $messages) {
            foreach ($messages as $message) {
                $this->errors[$heading][$key] = $message;
            }
        }
    }

    public function validateHeader()
    {
        $section = $this->msg['header'];
        $validator = Validator::make($section, [
            'documentCode' => 'required|string|in:703,704,705',
            'messageId' => 'required|string|max:35|alpha_num',
            'messageFunction' => 'required|integer|in:1,5,9',
            'documentPlace' => 'required|string|size:5',
            'documentDate' => 'required|date_format:Ymd',
            'documentDateFormat' => 'required|in:102',
            'numberOfOriginals' => 'required|integer|min:1|max:99',
            'blNumber' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            $this->mergeErrors('Header', $section, $validator->errors());
        }
    }

    public function validateReference()
    {
        $headings = [
            'FF' => 'Forwarders Ref.', 'MB' => 'System Internal Ref.', 'BM' => 'BOL Ref.', 'BN' => 'Booking Ref.', 'XRF' => 'Multiple REf.',
            'ERN' => 'Exporters REf.', 'AEG' => 'Customer Specification No.', 'AAC' => 'Accreditive No.', 'IP' => 'Importer Licence No.',
            'AER' => 'Project Spec. No', 'XAO' => 'CIS System Ref. (Orig)', 'XAR' => 'CIS System Ref. (Cnee)', 'PSN' => 'Sending Branch Ref.',
            'RMB' => 'Real Ocean Bill of Lading', 'OBF' => 'Orig Branch (Finance)', 'SKY' => 'Skybridge Flag'
        ];
        $sections = $this->msg['reference']['reference'];

        foreach ($sections as $section) {
            $validator = Validator::make($section, [
                'referenceQualifier' => 'required|string|in:FF,MB,BM,BN,XRF,ERN,AEG,AAC,IP,AER,XAO,XAR,PSN,RMB,OBF,SKY',
                'referenceNumber' => 'required|string|max:35',
            ]);

            if ($validator->fails()) {
                $this->mergeErrors($headings[$section['referenceQualifier']], $section, $validator->errors());
            }
        }
    }

    public function validateTHeader()
    {
        $section = $this->msg['theader'];
        $validator = Validator::make($section, [
            'transportStageQualifier' => 'required|string|in:20',
            'conveyanceReference' => 'nullable|string|max:17|alpha_num',
            'modeOfTransport' => 'required|string|in:10',
            'typeOfTransport' => 'required|string|in:11,13',
            'carrierId' => 'required|string:size:4',
            'carrierName' => 'required|string|max:35',
        ], $message = [
            'carrierId.required' => 'Carriers "keyname" must be set to the 4 char Carrier SCAC code',
        ]);

        if ($validator->fails()) {
            $this->mergeErrors('Transport Header', $section, $validator->errors());
        }
    }

    public function validateTDates()
    {
        $headings = ['11' => 'Shipped on board date', '118' => 'Booking date', '132' => 'Est. date of arrival', '133' => 'Est. date of departure', '136' => 'Departure date'];
        $sections = $this->msg['tdates']['tdate'];

        foreach ($sections as $section) {
            $validator = Validator::make($section, [
                'transportQualifier' => 'required|string|in:11,118,132,133,136',
                'transportDate' => 'required|date_format:Ymd',
                'transportDateFormat' => 'required|string|in:102',
            ]);

            if ($validator->fails()) {
                $this->mergeErrors($headings[$section['transportQualifier']], $section, $validator->errors());
            }
        }
    }

    public function validateTLocations()
    {
        $headings = ['5' => 'Port of loading', '7' => 'Final Destination', '9' => 'Place of loading', '12' => 'Port of discharge', '88' => 'Place of receipt'];
        $sections = $this->msg['tlocations']['tlocation'];

        foreach ($sections as $section) {
            $validator = Validator::make($section, [
                'placeQualifier' => 'required|string|in:5,7,9,12,88',
                'placeId' => 'required|string|size:5',
            ]);

            if ($validator->fails()) {
                $this->mergeErrors($headings[$section['placeQualifier']], $section, $validator->errors());
            }
        }
    }

    public function validateTAddress()
    {
        $headings = [
            'CN' => 'Consignee', 'CZ' => 'Consignor', 'N1' => 'Notify 1', 'N2' => 'Notify 2', 'OO' => 'Order of Shipper',
            'BA' => 'Booking Agent', 'FW' => 'Forwarder', 'CA' => 'Carrier', 'DO' => 'Document Recipient',
            'AP' => 'Applied to address', 'RC' => 'Real Consignee'
        ];
        $sections = $this->msg['taddress']['partnerAddr'];

        foreach ($sections as $section) {
            $validator = Validator::make($section, [
                'addressQualifier' => 'required|string|in:CN,CZ,N1,N2,OO,BA,FW,CA,DO,AP,RC',
                'addressId' => 'required|integer|min:1|max:999999',
                'addressStructure' => 'required|string|in:1,2',
                'name' => 'required|string|max:35',
                'streetAndNumber1' => 'nullable|string|max:35',
                'cityName' => 'nullable|string|max:35',
                'postCode' => 'nullable|string|max:9',
                'countryCode' => 'nullable|string|size:2',
                'comAddressId' => 'required|string|in:CN,CZ,N1,N2,OO,BA,FW,CA,DO,AP,RC',
                'contactFunction' => 'required|string|in:IC',
                'contactEmployeeName' => 'required|string|max:35',
                'communicationId' => 'required|string|in:TE',
                'communicationData' => 'required|string|max:25',
            ], $message = [
                'communicationDate.required' => 'Contact details max 25 chars',
            ]);

            if ($validator->fails()) {
                $this->mergeErrors($headings[$section['addressQualifier']], $section, $validator->errors());
            }
        }
    }

    public function validateGoods()
    {
        $itemNo = $this->msg['goodsDetails']['goods'][0]['itemNumber'] ?? 0;
        $noPkgs = $this->msg['goodsDetails']['goods'][0]['numberOfPackages'] ?? 0;
        $sections = $this->msg['goodsDetails']['goods'][0]['measurement']['measure'] ?? [];

        foreach ($sections as $section) {
            $validator = Validator::make($section, [
                'measureQualifier' => 'required|string|in:WT',
                'measureUnit' => 'required|string|in:KGM,MTQ',
                'measureValue' => 'required|numeric|min:0.01',
            ], $message = [
            ]);

            if ($validator->fails()) {
                $this->mergeErrors("Goods Details: Package $itemNo", $section, $validator->errors());
            }
        }
    }

    public function validatePackage()
    {
        $section = $this->msg['package'];
        $validator = Validator::make($section, [
            'totalNumberPackages' => 'required|integer',
            'totalGrossWeight' => 'required|numeric|min:.01',
            'weightMeasureQualifier' => 'required|string|in:KGM',
            'totalCube' => 'required|numeric|min:0.01',
            'cubeMeasureQualifier' => 'required|string|in:MTQ',
        ], [
        ]);

        if ($validator->fails()) {
            $this->mergeErrors('Package Totals', $section, $validator->errors());
        }
    }

    public function validateEquipmentUnit()
    {
        $units = $this->msg['equipmentUnit'];
        $unitCount = 0;
        foreach ($units as $unit) {
            $unitCount++;
            $validator = Validator::make(
                $unit,
                [
                    'equipmentQualifier' => 'required|string|in:CN,E',
                    'equipmentIdNumber' => 'required|string|max:17',
                    'sizeAndTypeText' => 'required|string|max:15',
                    'supplier' => 'required|string|in:1,2',
                    'movementPlan' => 'required|string|in:FCL/FCL,FCL/LCL,LCL/FCL,LCL/LCL',
                    'seal' => 'nullable|string|max:17',
                    'measurement.measure.*.measureQualifier' => 'required|in:WT,VOL',
                    'measurement.measure.*.movementDimensionCode' => 'required|in:T,U',
                    'measurement.measure.*.measureUnit' => 'required|in:KGM,MTQ',
                    'measurement.measure.*.measureValue' => 'required|numeric|min:0.001',
                ],
                [
                    'equipmentQualifier.required' => 'Container qualifier for unit '.$unitCount.' is required',
                    'equipmentQualifier.string' => 'Container qualifier for unit '.$unitCount.' qualifier must be a 1 or 2 character string',
                    'equipmentQualifier.in' => 'Container qualifier for unit '.$unitCount.' qualifier must be CN or E',
                    'equipmentIdNumber.required' => 'Container number for unit '.$unitCount.' is required',
                    'equipmentIdNumber.string' => 'Container number for unit '.$unitCount.' must be a string of characters',
                    'equipmentIdNumber.max' => 'Container number for unit '.$unitCount.' must max 17 characters',
                    'sizeAndTypeText.required' => "Size and type text for unit $unitCount required",
                    'supplier' => "Supplier for unit $unitCount required",
                    'movementPlan' => "FCL/ LCL for unit $unitCount required",
                    'seal' => "Seal Number for unit $unitCount required",
                    'measurement.measure.*.measureQualifier' => "Measurement qualifier for unit $unitCount required",
                    'measurement.measure.*.movementDimensionCode' => "Dimension qualifier (T/U) for unit $unitCount required",
                    'measurement.measure.*.measureUnit' => "Weight unit of measure for unit $unitCount required",
                    'measurement.measure.*.measureValue' => "Weight for unit $unitCount required",
                ]
            );

            if ($validator->fails()) {
                $this->mergeErrors('Equipment Unit', 'Unit: '.$unitCount, $validator->errors());
            }
        }
    }

    public function validateDTerms()
    {
        $section = $this->msg['dterms'];
        $validator = Validator::make($section, [
            'termsOfDeliveryCode' => 'required|string|size:3',
            'termsOfDeliveryCodeList' => 'required|string|size:3',
            'termsLocation' => 'required|string|max:70',
        ]);

        if ($validator->fails()) {
            $this->mergeErrors('Delivery Terms', $section, $validator->errors());
        }
    }

}
