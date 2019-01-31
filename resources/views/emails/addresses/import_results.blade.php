@extends('layouts.mail')

@section('content')

<h1>Recipient Address Import</h1>

<p><b>Records Inserted:</b> {{$results['summary']['inserted']}}</p>
<p><b>Records Failed:</b> {{$results['summary']['failed']}}</p>

@if($results['summary']['failed'] > 0)
<p>Please correct the errors highlighted below and try again. Any addresses that were imported successfully will be ignored if you upload the same file again.</p>
@endif

<table border="0" cellspacing="0" width="100%" class="table">

    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Company Name</th>
            <th>Address 1</th>
            <th>Address 2</th>
            <th>Address 3</th>
            <th>City</th>
            <th>State</th>
            <th>Postcode</th>            
            <th>Country</th>
            <th>Telephone</th>
            <th>Email</th>            
            <th>Type</th>
            <th>Status</th>
            <th>Errors</th>
        </tr>
    </thead>

    @foreach($results['rows'] as $result)

    <tr>
        <td>{{$loop->iteration}}</td>
        @if(isset($result['data']))        
        <td @if(isset($result['fields_in_error']['name'])) class="field-error" @endif>{{$result['data']['name']}}</td>
        <td @if(isset($result['fields_in_error']['company_name'])) class="field-error" @endif>{{$result['data']['company_name']}}</td>
        <td @if(isset($result['fields_in_error']['address1'])) class="field-error" @endif>{{$result['data']['address1']}}</td>
        <td @if(isset($result['fields_in_error']['address2'])) class="field-error" @endif>{{$result['data']['address2']}}</td>
        <td @if(isset($result['fields_in_error']['address3'])) class="field-error" @endif>{{$result['data']['address3']}}</td>
        <td @if(isset($result['fields_in_error']['city'])) class="field-error" @endif>{{$result['data']['city']}}</td>
        <td @if(isset($result['fields_in_error']['state'])) class="field-error" @endif>{{$result['data']['state']}}</td>
        <td @if(isset($result['fields_in_error']['postcode'])) class="field-error" @endif>{{$result['data']['postcode']}}</td>
        <td @if(isset($result['fields_in_error']['country_code'])) class="field-error" @endif>{{$result['data']['country_code']}}</td>
        <td @if(isset($result['fields_in_error']['telephone'])) class="field-error" @endif>{{$result['data']['telephone']}}</td>
        <td @if(isset($result['fields_in_error']['email'])) class="field-error" @endif>{{$result['data']['email']}}</td>
        <td @if(isset($result['fields_in_error']['type'])) class="field-error" @endif>             
             @if($result['data']['type'] == 'c')
             Commercial
             @else
             Residential
             @endif
    </td>
    <td>
        @if(isset($result['errors']))
        <span class="error">Failed</span>
        @else
        <span class="inserted">Inserted</span>
        @endif                        
    </td>
    <td class="error-summary">
        @if(isset($result['errors']))     
        @foreach($result['errors'] as $error)
        {{$error}}<br>
        @endforeach    
        @endif              
    </td>
    @elseif(isset($result['errors']))
    <td class="fade">n/a</td>
    <td class="fade">n/a</td>
    <td class="fade">n/a</td>
    <td class="fade">n/a</td>
    <td class="fade">n/a</td>
    <td class="fade">n/a</td>
    <td class="fade">n/a</td>
    <td class="fade">n/a</td>
    <td class="fade">n/a</td>
    <td class="fade">n/a</td>
    <td class="fade">n/a</td>
    <td class="fade">n/a</td>
    <td class="error">Failed</td>
    <td class="error-summary">
        @if(isset($result['errors']))     
        @foreach($result['errors'] as $error)
        {{$error}}<br>
        @endforeach    
        @endif              
    </td>        
    @endif
</tr>

@endforeach

</table>

@endsection