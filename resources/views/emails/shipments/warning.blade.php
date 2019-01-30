@extends('layouts.mail')

@section('content')

<h3 class="error">{{$subject}}</h3>

<p>To view the full shipment details please visit <a href="{{ url('/shipments/' . $shipment->id) }}">{{ url('/shipments/' . $shipment->id) }}</a></p>

<table border="0" cellspacing="0" width="50%" class="table">
    <tbody>
        <tr>
            <td>Consignment</td>
            <td><a href="{{ url('/shipments/' . $shipment->id) }}">{{$shipment->consignment_number}}</a></td>
        </tr>
        <tr>
            <td>Carrier</td>
            <td>{{$shipment->carrier->name}}</td>
        </tr>
        <tr>
            <td>Carrier Reference</td>
            <td>{{$shipment->carrier_consignment_number}}</td>
        </tr>
        <tr>
            <td>Sender</td>
            <td>{{$shipment->sender_name}}, {{$shipment->sender_company_name}}, {{$shipment->sender_address1}}, {{$shipment->sender_city}} {{$shipment->sender_postcode}}</td>
        </tr> 
        <tr class="{{$countryClass}}">
            <td>Recipient</td>
            <td>{{$shipment->recipient_name}}, {{$shipment->recipient_address1}}, {{$shipment->recipient_city}} <strong>{{$shipment->recipient_country}}</strong></td>
        </tr>

        @if($subject == 'Loss Making Shipment')
        
        <tr class="error">
            <td>Profit/Loss</td>
            <td>{{$shipment->profit_formatted}}</td>
        </tr> 
        
        <tr class="error">
            <td>Margin</td>
            <td>{{$shipment->margin}}</td>
        </tr> 
        
        @endif

        <tr class="{{$valueClass}}">
            <td>Value</td>
            <td>{{number_format($shipment->customs_value, 2)}} {{$shipment->customs_value_currency_code}}</td>
        </tr>        
        <tr>
            <td>Bill Shipping</td>
            <td>{{ucfirst($shipment->bill_shipping)}} {{$shipment->bill_shipping_account}}</td>
        </tr>
        <tr>
            <td>Bill Tax And Duty</td>
            <td>{{ucfirst($shipment->bill_tax_duty)}} {{$shipment->bill_tax_duty_account}}</td>
        </tr>
    </tbody>
</table>

@endsection