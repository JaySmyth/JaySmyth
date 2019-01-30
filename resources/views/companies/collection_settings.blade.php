@php if (session()->has('messageBags')) { $messageBags = session('messageBags'); } @endphp

@extends('layouts.app')

@section('content')

<h2>Collection / Delivery Settings: {{$company->company_name}}</h2>

<hr>

{!! Form::model($company, ['method' => 'POST', 'url' => 'companies/' . $company->id . '/collection-settings', 'class' => '', 'autocomplete' => 'off']) !!}

<div class="form-group row">
    <label class="col-2 col-form-label text-large">
        Bulk Collections: <abbr title="This information is required.">*</abbr>
    </label>
    <div class="col-2">
        {!! Form::select('bulk_collections', dropDown('boolean'), old('bulk_collections'), array('id' => 'bulk_collections', 'class' => 'form-control')) !!}
    </div>
</div>

<hr>

<div class="radio text-large">
    <label>
        {!! Form::radio('use_default', 1, $useDefaults) !!}
        Use default settings for {{$company->postcode}}
    </label>
</div>
<div class="radio text-large">
    <label>
        {!! Form::radio('use_default', 0, $company->collectionSettings->count()) !!}
        Define settings
    </label>
</div>

<table class="table table-striped table-bordered table-sm mt-3">
    <thead>
        <tr>
            <th>Day</th>
            <th>Collection Time</th>
            <th>Delivery Time</th>
            <th>Collection Route</th>
            <th>Delivery Route</th>
        </tr>
    </thead>
    <tbody>
        @for($i = 0; $i <= 6; $i++)
        <tr>
            <td>{{intToDay($i)}}</td>
            <td @if(isset($messageBags[$i]) && $messageBags[$i]->has('collection_time')) class="has-danger" @endif>                                        
                {!! Form::Text('settings['.$i.'][collection_time]', old('settings['.$i.'][collection_time]', $company->collection_settings_array[$i]['collection_time']), ['class' => 'form-control form-control-sm', 'placeholder' => 'Collection time', 'maxlength' => 8]) !!}
            </td>
            <td @if(isset($messageBags[$i]) && $messageBags[$i]->has('delivery_time')) class="has-danger" @endif>  
                {!! Form::Text('settings['.$i.'][delivery_time]', old('settings['.$i.'][delivery_time]', $company->collection_settings_array[$i]['delivery_time']), ['class' => 'form-control form-control-sm', 'placeholder' => 'Delivery time', 'maxlength' => 8]) !!}
            </td>
            <td @if(isset($messageBags[$i]) && $messageBags[$i]->has('collection_route')) class="has-danger" @endif>  
                {!! Form::Text('settings['.$i.'][collection_route]', old('settings['.$i.'][collection_route]', $company->collection_settings_array[$i]['collection_route']), ['class' => 'form-control form-control-sm', 'placeholder' => 'Collection route', 'maxlength' => 6]) !!}
            </td>
            <td @if(isset($messageBags[$i]) && $messageBags[$i]->has('delivery_route')) class="has-danger" @endif>  
                {!! Form::Text('settings['.$i.'][delivery_route]', old('settings['.$i.'][delivery_route]', $company->collection_settings_array[$i]['delivery_route']), ['class' => 'form-control form-control-sm', 'placeholder' => 'Delivery route', 'maxlength' => 6]) !!}
            </td>
        </tr>
        @endfor
    </tbody>
</table>

@include('partials.submit_buttons', ['submitButtonText' => 'Save Settings'])

{!! Form::Close() !!}
@endsection