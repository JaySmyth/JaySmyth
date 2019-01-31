<table class="table table-bordered table-striped table-sm">
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th>Consignment</th>
            <th>Shipper</th>
            <th>Recipient</th>
            <th>Charges</th>
            <th>Details</th>
        </tr>
    </thead>
    <tbody>

        @foreach($shipments as $shipment)

        <tr>
            <td class="text-center">{{$loop->iteration}}</td>
            <td>

                @if(isset($form))
                @if($shipment->shipping_charge > 0 || strtolower($shipment->service->code) == 'ipf')
                {!! Form::hidden('shipments[]', $shipment->id) !!}            
                @endif
                @if($shipment->company_id == '4')
                {!! Form::hidden('shipments[]', $shipment->id) !!}            
                @endif
                @endif

                <div class="row">
                    <div class="col-sm-5">Consignment</div>
                    <div class="col-sm-7"><a href="{{ url('/shipments', $shipment->id) }}">{{$shipment->consignment_number}}</a></div>
                </div>
                <div class="row">
                    <div class="col-sm-5">Carrier Ref</div>
                    <div class="col-sm-7">{{$shipment->carrier_consignment_number}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-5">Customer Ref</div>
                    <div class="col-sm-7">{{$shipment->shipment_reference}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-5">Bill Shipping</div>
                    <div class="col-sm-7">{{$shipment->bill_shipping}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-5">Bill Duty</div>
                    <div class="col-sm-7">{{$shipment->bill_duty}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-5">SCS Acct No</div>
                    <div class="col-sm-7">{{$shipment->company->scs_code}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-5">Ship Date</div>
                    <div class="col-sm-7">

                        @if($shipment->ship_date->diffInDays() > 3)

                        <strong class="text-danger">                            
                            {{$shipment->ship_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}
                        </strong>

                        <span class="fas fa-info-circle" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="Ship date not recent - check not already invoiced"></span>

                        @else
                        {{$shipment->ship_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}
                        @endif

                    </div>
                </div>
            </td>
            <td>
                @if ($shipment->sender_company_name){{$shipment->sender_company_name}}<br>@endif
                @if ($shipment->sender_address1){{$shipment->sender_address1}}<br>@endif
                @if ($shipment->sender_address2){{$shipment->sender_address2}}<br>@endif
                @if ($shipment->sender_address3){{$shipment->sender_address3}}<br>@endif
                @if ($shipment->sender_city){{$shipment->sender_city}}<br>@endif
                {{$shipment->sender_state}} {{$shipment->sender_postcode}}<br>
                {{$shipment->sender_country}}<br>
            </td>
            <td>
                @if ($shipment->recipient_name){{substr($shipment->recipient_name,0,35)}} - ({{strtoupper($shipment->recipient_type)}})<br>@endif
                @if ($shipment->recipient_company_name){{substr($shipment->recipient_company_name,0,35)}}<br>@endif
                @if ($shipment->recipient_address1){{substr($shipment->recipient_address1,0,35)}}<br>@endif
                @if ($shipment->recipient_address2){{substr($shipment->recipient_address2,0,35)}}<br>@endif
                @if ($shipment->recipient_address3){{substr($shipment->recipient_address3,0,35)}}<br>@endif
                @if ($shipment->recipient_city){{$shipment->recipient_city}}<br>@endif
                {{$shipment->recipient_state}} {{$shipment->recipient_postcode}}<br>
                {{$shipment->recipient_country}}<br>
            </td>
            <td>
                <table>
                    @if(isset($shipment->quoted_array['costs']) && !empty($shipment->quoted_array['costs']))
                    @foreach(consolidateCharges($shipment->quoted_array['costs']) as $charge)
                    @if($charge['value']<>0)
                    <tr>
                        <td style="width: 10%">Cost : {{$charge['code']}}</td>
                        <td style="width: 10%" class='text-right'>{{number_format($charge['value'],2)}}</td>
                    </tr>
                    @endif
                    @endforeach
                    @endif
                    <tr>
                        <td style="width: 10%"><b>Total Cost</b></td>
                        <td style="width: 10%" class='text-right'>

                            <b>
                                {{number_format($shipment->quoted_array['shipping_cost'],2)}}

                                @if(isset($shipment->quoted_array['cost_currency']))
                                {{$shipment->quoted_array['cost_currency']}}
                                @endif
                            </b>

                        </td>
                    </tr>
                    @if(isset($shipment->quoted_array['sales']) && !empty($shipment->quoted_array['sales']))
                    @foreach(consolidateCharges($shipment->quoted_array['sales']) as $charge)
                    @if($charge['value']<>0)
                    <tr>
                        <td style="width: 10%">Sale : {{$charge['code']}}</td>
                        <td style="width: 10%" class='text-right'>{{number_format($charge['value'],2)}}</td>
                    </tr>
                    @endif
                    @endforeach
                    @endif
                    <tr>
                        <td style="width: 10%"><b>Total Sale</b></td>
                        <td style="width: 10%" class='text-right'>

                            <b>
                                {{number_format($shipment->quoted_array['shipping_charge'],2)}}

                                @if(isset($shipment->quoted_array['sales_currency']))
                                {{$shipment->quoted_array['sales_currency']}}
                                @endif
                            </b>

                        </td>
                    </tr>

                    <!-- If any errors then display -->
                    @if(isset($shipment->quoted_array['errors']) && !empty($shipment->quoted_array['errors']))
                    <tr class='bg-danger text-white'>

                        <td colspan="2">
                            Errors :
                            @foreach($shipment->quoted_array['errors'] as $error)
                            {{$error}}<br>
                            @endforeach
                        </td>
                    </tr>
                    @endif

                </table>
            </td>
            <td>
                <div class="row">
                    <div class="col-sm-5">Packaging</div>
                    <div class="col-sm-7">{{(!empty($shipment->quoted_array['sales_packaging']) ? $shipment->quoted_array['sales_packaging'] : '')}} x {{$shipment->pieces}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-5">Weight</div>
                    <div class="col-sm-7">{{$shipment->weight}} / Vol : {{$shipment->volumetric_weight}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-5">Costs Zone</div>
                    <div class="col-sm-7">{{ (isset($shipment->quoted_array['costs_zone'])) ? $shipment->quoted_array['costs_zone'] : ''}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-5">Sales Zone</div>
                    <div class="col-sm-7">{{ (isset($shipment->quoted_array['sales_zone'])) ? $shipment->quoted_array['sales_zone'] : ''}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-5">Vat Code</div>
                    <div class="col-sm-7">{{$shipment->quoted_array['sales_vat_code']}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-5">Service</div>
                    <div class="col-sm-7"><span class="text-uppercase badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->service->name ?? 'Unknown'}}">{{$shipment->service->code ?? ''}}</span></div>
                </div>
                <div class="row">
                    <div class="col-sm-5">Carrier</div>
                    <div class="col-sm-7"><span class="text-uppercase badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->carrier->name ?? 'Unknown'}}">{{$shipment->carrier->name ?? ''}}</span></div>
                </div>
            </td>
        </tr>

        @endforeach

        <tr class="bg-secondary text-white text-large">
            <td>&nbsp;</td>
            <td>Total Shipments: {{number_format($shipments->count())}}</td>
            <td>Total Pieces: {{number_format($shipments->sum('pieces'))}}</td>
            <td>Total Weight: {{number_format($shipments->sum('weight'), 1)}}</td>
            <td>Total Costs: {{number_format($shipments->sum('shipping_cost'), 2)}}</td>
            <td>Total Sales: {{number_format($shipments->sum('shipping_charge'), 2)}}</td>    
        </tr>
    </tbody>
</table>