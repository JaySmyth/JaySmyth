@extends('layouts.app')

@section('content')

<h2>Update Seal Number: {{$container->number}}</h2>



{!! Form::model($container, ['method' => 'POST', 'url' => 'sea-freight/' . $shipment->id . '/edit-seal-number/' . $container->id, 'class' => '', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

<div class="row">
    <div class="col-sm-6">

        <div class="form-group row{{ $errors->has('seal_number') ? ' has-danger' : '' }}">  

            <label class="col-sm-5  col-form-label">Seal Number: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::Text('seal_number', old('seal_number'), ['id' => 'seal_number', 'class' => 'form-control', 'maxlength' => '50']) !!}

                @if ($errors->has('seal_number'))
                <span class="form-text">
                    <strong>{{ $errors->first('seal_number') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row buttons-main">
            <div class="col-sm-5">&nbsp;</div>
            <div class="col-sm-6">
                <a class="back btn btn-secondary" role="button">Cancel</a>
                <button size="submit" class="btn btn-primary">Update Seal Number</button>
            </div>
        </div>
    </div>

</div>

{!! Form::Close() !!}

@endsection