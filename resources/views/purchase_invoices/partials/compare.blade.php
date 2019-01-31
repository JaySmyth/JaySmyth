@if(!isset($email))
<h4 class="mb-2">{{$title}} <span class="badge badge-pill badge-sm badge-secondary">{{count($lines)}}</span></h4>	
@endif

@if(isset($email))
<table border="0" cellspacing="0" width="100%" class="table table-striped">
    @else
    <table class="table table-striped table-bordered table-sm">
        @endif

        <thead>
            <tr class="active">
                <th>#</th>
                <th>Consignment</th>
                <th>SCS Job Number</th>
                <th>Shipper / Consignor</th>   
                <th class="text-center">VC</th>
                <th class="text-right">FRT Carrier</th>
                <th class="text-right">FRT IFS</th>
                <th class="text-right">FSC Carrier</th>
                <th class="text-right">FSC IFS</th>            
                <th class="text-right">Other Carrier</th>
                <th class="text-right">Other IFS</th>            
                <th class="text-right">Total Carrier</th>
                <th class="text-right">Total IFS</th>
                <th class="text-right">Difference</th>                
            </tr>
        </thead>
        <tbody>
            @foreach($lines as $line)
            <tr>
                <td><a href="{{ url('/purchase-invoices/' . $invoice->id . '/detail#line-' . $line->id) }}">{{$loop->iteration}}</a></td>
                <td>
                    @if($line->shipment_id)         
                    <a href="{{ url('/shipments', $line->shipment->id) }}" class="consignment-number">{{$line->carrier_tracking_number}}</a>                                        
                    @elseif($line->carrier_tracking_number)               
                    {{$line->carrier_tracking_number}}                
                    @else
                    Unknown
                    @endif
                </td>
                <td>
                    @if($line->scs_job_number)
                    {{$line->scs_job_number}}
                    @else
                    <span class="text-danger">Unknown</span>
                    @endif
                </td>
                <td>
                    @if($line->shipment_id)
                    <a href="{{ url('/companies', $line->shipment->company_id) }}">{{$line->shipment->company->company_name}}</a>
                    @else
                    @if($line->sender_company_name)
                    {{$line->sender_company_name}}
                    @elseif($line->carrier_service)
                    {{$line->carrier_service}}
                    @else
                    Unknown
                    @endif
                    @endif
                </td>
                <td class="text-center">{{$line->scs_vat_code}}</td>
                <td class="text-right" title="FRT Carrier">
                    @if($line->freight_overcharge)
                    <span class="text-danger">{{number_format($line->total_freight, 2)}}</span>
                    @else
                    {{number_format($line->total_freight, 2)}}
                    @endif
                </td>
                <td class="text-right" title="FRT IFS">{{number_format($line->total_freight_ifs, 2)}}</td>
                <td class="text-right" title="FSC Carrier">
                    @if($line->fuel_surcharge_overcharge)
                    <span class="text-danger">{{number_format($line->total_fuel_surcharge, 2)}}</span>
                    @else
                    {{number_format($line->total_fuel_surcharge, 2)}}
                    @endif
                </td>
                <td class="text-right" title="FSC IFS">{{number_format($line->total_fuel_surcharge_ifs, 2)}}</td>
                <td class="text-right" title="Other Carrier">
                    @if($line->other_charges_overcharge)
                    <span class="text-danger">{{number_format($line->total_other_charges, 2)}}</span>
                    @else
                    {{number_format($line->total_other_charges, 2)}}
                    @endif
                </td>
                <td class="text-right" title="Other IFS">{{number_format($line->total_other_charges_ifs, 2)}}</td>
                <td class="text-right" title="Total Carrier">{{number_format($line->total, 2)}}</td>
                <td class="text-right" title="Total IFS">{{number_format($line->total_ifs, 2)}}</td>            
                <td class="text-right" title="Difference"><span class="{{$line->styling_class}}">{{$line->difference_formatted}}</span></td>                
            </tr>
            @endforeach
        </tbody>
    </table>