<div class="col-sm-6">

    @if(Auth::user()->hasMultipleCompanies())

    <div class="form-group row{{ $errors->has('company_id') ? ' has-danger' : '' }}">

        <label class="col-sm-5  col-form-label">
            Shipper: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::select('company_id', dropDown('enabledSites', 'Please select'), old('company_id'), array('id' => 'company_id', 'class' => 'form-control')) !!}

            @if ($errors->has('company_id'))
            <span class="form-text">
                <strong>{{ $errors->first('company_id') }}</strong>
            </span>
            @endif

        </div>
    </div>

    @else
    {!! Form::hidden('company_id', Auth::user()->company_id, array('id' => 'company_id')) !!}
    @endif

    <div class="form-group row{{ $errors->has('reference') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Shipment Reference: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::Text('reference', old('reference'), ['id' => 'reference', 'class' => 'form-control', 'maxlength' => '30']) !!}

            @if ($errors->has('reference'))
            <span class="form-text">
                <strong>{{ $errors->first('reference') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('final_destination') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Final Destination: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::Text('final_destination', old('final_destination'), ['id' => 'final_destination', 'class' => 'form-control', 'maxlength' => '100']) !!}

            @if ($errors->has('final_destination'))
            <span class="form-text">
                <strong>{{ $errors->first('final_destination') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('final_destination_country_code') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Final Destination Country: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::select('final_destination_country_code', dropDown('countries', 'Please select'), old('final_destination_country_code'), array('id' => 'final_destination_country_code', 'class' => 'form-control')) !!}

            @if ($errors->has('final_destination_country_code'))
            <span class="form-text">
                <strong>{{ $errors->first('final_destination_country_code') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('required_on_dock_date') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Required On Dock Date: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::select('required_on_dock_date', dropDown('datesLong'), old('required_on_dock_date', date('d-m-Y', strtotime('today'))), array('id' => 'required_on_dock_date', 'class' => 'form-control')) !!} 

            @if ($errors->has('required_on_dock_date'))
            <span class="form-text">
                <strong>{{ $errors->first('required_on_dock_date') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('weight') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Gross Weight (KG): <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::Text('weight', old('weight'), ['id' => 'weight', 'class' => 'form-control decimal-only', 'maxlength' => '10']) !!}

            @if ($errors->has('weight'))
            <span class="form-text">
                <strong>{{ $errors->first('weight') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('value') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Shipment Value: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-3">
            {!! Form::Text('value', old('value'), ['id' => 'value', 'class' => 'form-control decimal-only', 'maxlength' => '10']) !!}

            @if ($errors->has('value'))
            <span class="form-text">
                <strong>{{ $errors->first('value') }}</strong>
            </span>
            @endif

        </div>

        <div class="col-sm-3">                
            {!! Form::select('value_currency_code', dropDown('currencies'), null, array('id' => 'value_currency_code', 'class' => 'form-control')) !!}                
        </div>
    </div>

    <div class="form-group row{{ $errors->has('special_instructions') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Special Instructions:
        </label>

        <div class="col-sm-6">
            {!! Form::Textarea('special_instructions', old('special_instructions'), ['id' => 'special_instructions', 'class' => 'form-control', 'rows' => 4, 'maxlength' => 255]) !!}

            @if ($errors->has('special_instructions'))
            <span class="form-text">
                <strong>{{ $errors->first('special_instructions') }}</strong>
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