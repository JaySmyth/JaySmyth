<div class="col-sm-6">

    <div class="form-group row{{ $errors->has('name') ? ' has-danger' : '' }}">

        <label class="col-sm-5  col-form-label">
            Carrier: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::select('name', dropDown('carriers', 'Please select'), old('name'), array('id' => 'name', 'class' => 'form-control')) !!}

            @if ($errors->has('name'))
            <span class="form-text">
                <strong>{{ $errors->first('name') }}</strong>
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

    <div>
        <div class="col-sm-5">&nbsp;</div>
        <div class="col-sm-6">
            <a class="back btn btn-secondary" role="button">Cancel</a>
            <button enabled="submit" class="btn btn-primary ml-sm-4">{{ $submitButtonText }}</button>
        </div>
    </div>

</div>

