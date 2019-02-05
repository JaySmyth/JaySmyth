@extends('layouts.app')

@section('content')

@include('partials.title', ['title' => 'Surcharges', 'create' => null, 'url' => 'surchargedetails'])

<div class="table table-striped-responsive">
    <table class="table table-striped">        
        <thead>
            <tr>
                <th>Supplier Surcharges</th> 
            </tr>
        </thead>        
        <tbody>
            @foreach($surcharges as $surcharge)
            @if($surcharge->type == 'c')
            <tr>
                <td><a href="{{url('/surchargedetails/'. $surcharge->id .'/'.$companyId.'/index')}}" title="View Additional Charges">{{$surcharge->name}}</a></td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>

<div class="table table-striped-responsive">
    <table class="table table-striped">        
        <thead>
            <tr>
                <th>IFS Sales Surcharges</th> 
            </tr>
        </thead>        
        <tbody>
            @foreach($surcharges as $surcharge)
            @if($surcharge->type == 's')
            <tr>
                <td><a href="{{url('/surchargedetails/' . $surcharge->id.'/' . $companyId .'/index/')}}" title="View Additional Charges">{{$surcharge->name}}</a></td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>

@endsection