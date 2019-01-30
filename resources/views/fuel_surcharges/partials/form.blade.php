<div class="col-sm-6">

    <div class="form-group row{{ $errors->has('carrier_id') ? ' has-danger' : '' }}">

        <label class="col-sm-5  col-form-label">
            Carrier: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::select('carrier_id', dropDown('carriers', 'Please select'), old('carrier_id'), array('id' => 'carrier_id', 'class' => 'form-control')) !!}

            @if ($errors->has('carrier_id'))
            <span class="form-text">
                <strong>{{ $errors->first('carrier_id') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('service_id') ? ' has-danger' : '' }}">

        <label class="col-sm-5  col-form-label">
            Service: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::select('service_id', dropDown('services', 'Please select'), old('service_id'), array('id' => 'service_id', 'class' => 'form-control')) !!}

            @if ($errors->has('service_id'))
            <span class="form-text">
                <strong>{{ $errors->first('service_id') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('percentage') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Percentage: <abbr title="This information is required.">*</abbr>            
        </label>

        <div class="col-sm-6">
            {!! Form::Text('percentage', old('percentage'), ['id' => 'percentage', 'class' => 'form-control', 'maxlength' => '5']) !!}

            @if ($errors->has('percentage'))
            <span class="form-text">
                <strong>{{ $errors->first('percentage') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('date_from') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Date From:
        </label>

        <div class="col-sm-6">
            {!! Form::select('date_from', dropDown('dates'), old('date_from', date('d-m-Y', strtotime('today'))), array('id' => 'date_from', 'class' => 'form-control')) !!} 

            @if ($errors->has('date_from'))
            <span class="form-text">
                <strong>{{ $errors->first('date_from') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('date_to') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Date To:
        </label>

        <div class="col-sm-6">
            {!! Form::select('date_to', dropDown('dates'), old('date_to', date('d-m-Y', strtotime('today'))), array('id' => 'date_to', 'class' => 'form-control')) !!} 

            @if ($errors->has('date_to'))
            <span class="form-text">
                <strong>{{ $errors->first('date_to') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="row">
        <div class="col-sm-5">&nbsp;</div>
        <div class="col-sm-6">
            <a class="back btn btn-secondary" role="button">Cancel</a>
            <button enabled="submit" class="btn btn-primary ml-sm-4">{{ $submitButtonText }}</button>
        </div>
    </div>

</div>

