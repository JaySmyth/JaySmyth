@extends('layouts.app')

@section('content')

<h2>{{$report->name}} <small>{{$manifest->number ?? ''}}</small>
    <div class="float-right">
        {!! Form::Open(['id' => 'manifest', 'url' => Request::path(), 'method' => 'get', 'class' => '', 'autocomplete' => 'off']) !!}
        {!! Form::select('manifest_id', $dropdown, Input::get('manifest_id'), array('id' => 'manifest_id', 'placeholder' => 'Not manifested', 'class' => 'form-control')) !!}
        {!! Form::Close() !!}
    </div>
</h2>

<hr>

<h4 class="mb-2">Charles de Gaulle - {{$range}}
    <div class="float-right">{{$report->name}}</div>
</h4>

<table class="table table-striped table-bordered table-sm">
    <thead>
        <tr>
            <th>#</th>                             
            <th>Consignment</th>                
            <th>Sender</th>            
            <th>Recipient</th>
            <th>Description of Goods</th>
            <th>Account</th>
            <th class="text-right">Pieces</th>
            <th class="text-right">Weight ({{strtoupper($report->depot->localisation->weight_uom)}})</th>
            <th class="text-right">Value (GBP)</th>
        </tr>
    </thead>
    <tbody>

        @foreach($parisShipments as $shipment)

        <tr>
            <td>{{$loop->iteration}}</td>                
            <td><a href="{{ url('/shipments', $shipment->id) }}" class="consignment-number">{{$shipment->carrier_consignment_number}}</a></td>                  
            <td>
                {{$shipment->sender_company_name ?: $shipment->sender_name}}<br>
                {{$shipment->sender_address1}}<br>
                @if($shipment->sender_address2){{$shipment->sender_address2}}<br> @endif
                {{$shipment->sender_state}}<br>
                {{$shipment->sender_city}} {{$shipment->sender_postcode}}<br>
                {{$shipment->sender_country}}
            </td>

            <td>
                {{$shipment->recipient_company_name ?: $shipment->recipient_name}}<br>
                {{$shipment->recipient_address1}}<br>
                @if($shipment->recipient_address2){{$shipment->recipient_address2}}<br> @endif
                {{$shipment->recipient_state}}<br>
                {{$shipment->recipient_city}} {{$shipment->recipient_postcode}}<br>
                {{$shipment->recipient_country}}
            </td>

            <td>   
                @if($shipment->contents->count() > 0)
                @foreach($shipment->contents as $content)                    
                Pkg {{$content->package_index}} - {{$content->description}}<br>
                @endforeach                                       
                @else
                @foreach ($shipment->packages as $package)
                Pkg {{$loop->iteration}} - {{$shipment->goods_description}}{{$shipment->documents_description}}<br>
                @endforeach
                @endif                   
            </td>
            <td>{{$shipment->bill_shipping_account}}</td>
            <td class="text-right">{{$shipment->pieces}}</td>
            <td class="text-right">{{$shipment->weight}} </td>            
            <td class="text-right">
                @if($shipment->customs_value_gbp)
                {{$shipment->customs_value_gbp}}
                @else
                <span class="text-danger">Check {{$shipment->customs_value_currency_code}} exists and rate set</span>
                @endif  
            </td> 
        </tr>

        @endforeach

    <thead>
        <tr class="text-large bg-secondary text-white">
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th class="text-right">{{$parisShipments->sum('pieces')}}</th>
            <th class="text-right">{{number_format($parisShipments->sum('weight'),2)}}</th>
            <th class="text-right">{{number_format($parisShipments->sum('customs_value_gbp'),2)}}</th>
        </tr>
    </thead>
</tbody>
</table>

<h4 class="mt-5 mb-2">Memphis - {{$range}}
    <div class="float-right">{{$report->name}}</div>
</h4>

<table class="table table-striped table-bordered table-sm">
    <thead>
        <tr>
            <th>#</th>                             
            <th>Consignment</th>                
            <th>Sender</th>            
            <th>Recipient</th>
            <th>Description of Goods</th>
            <th>Account</th>
            <th class="text-right">Pieces</th>
            <th class="text-right">Weight ({{strtoupper($report->depot->localisation->weight_uom)}})</th>
            <th class="text-right">Value (GBP)</th>
        </tr>
    </thead>
    <tbody>

        @foreach($memphisShipments as $shipment)

        <tr>
            <td>{{$loop->iteration}}</td>                
            <td><a href="{{ url('/shipments', $shipment->id) }}" class="consignment-number">{{$shipment->carrier_consignment_number}}</a></td>                  
            <td>
                {{$shipment->sender_company_name ?: $shipment->sender_name}}<br>
                {{$shipment->sender_address1}}<br>
                @if($shipment->sender_address2){{$shipment->sender_address2}}<br> @endif
                {{$shipment->sender_state}}<br>
                {{$shipment->sender_city}} {{$shipment->sender_postcode}}<br>
                {{$shipment->sender_country}}
            </td>

            <td>
                {{$shipment->recipient_company_name ?: $shipment->recipient_name}}<br>
                {{$shipment->recipient_address1}}<br>
                @if($shipment->recipient_address2){{$shipment->recipient_address2}}<br> @endif
                {{$shipment->recipient_state}}<br>
                {{$shipment->recipient_city}} {{$shipment->recipient_postcode}}<br>
                {{$shipment->recipient_country}}
            </td>

            <td>   
                @if($shipment->contents->count() > 0)
                @foreach($shipment->contents as $content)                    
                Pkg {{$content->package_index}} - {{$content->description}}<br>
                @endforeach                                       
                @else
                @foreach ($shipment->packages as $package)
                Pkg {{$loop->iteration}} - {{$shipment->goods_description}}{{$shipment->documents_description}}<br>
                @endforeach
                @endif                   
            </td>
            <td>{{$shipment->bill_shipping_account}}</td>
            <td class="text-right">{{$shipment->pieces}}</td>
            <td class="text-right">{{$shipment->weight}} </td>            
            <td class="text-right">
                @if($shipment->customs_value_gbp)
                {{$shipment->customs_value_gbp}}
                @else
                <span class="text-danger">Check {{$shipment->customs_value_currency_code}} exists and rate set</span>
                @endif  
            </td> 
        </tr>

        @endforeach

    <thead>
        <tr class="text-large bg-secondary text-white">
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th class="text-right">{{$memphisShipments->sum('pieces')}}</th>
            <th class="text-right">{{number_format($memphisShipments->sum('weight'),2)}}</th>
            <th class="text-right">{{number_format($memphisShipments->sum('customs_value_gbp'),2)}}</th>
        </tr>
    </thead>
</tbody>
</table>


@endsection