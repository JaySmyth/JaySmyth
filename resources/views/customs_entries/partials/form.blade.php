<div class="col-sm-6">

    <div class="form-group row{{ $errors->has('company_id') ? ' has-danger' : '' }}">

        <label class="col-sm-5  col-form-label">
            Customer: <abbr title="This information is required.">*</abbr>
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

    <div class="form-group row{{ $errors->has('reference') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Customer's Reference: <span class="fas fa-info-circle" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="An identifier that the customer will recognise. A 3rd party consignment number, PO etc."></span>
            <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::Text('reference', old('reference'), ['id' => 'reference', 'class' => 'form-control', 'maxlength' => '20']) !!}

            @if ($errors->has('reference'))
            <span class="form-text">
                <strong>{{ $errors->first('reference') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('consignment_number') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Consignment Number:
            <span class="fas fa-info-circle" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="This should be an 'IFS' consignment number if raised by IFS (not a 3rd party consignment number). If an import shipment, a 3rd party consignment number can be used."></span>
        </label>

        <div class="col-sm-6">
            {!! Form::Text('consignment_number', old('consignment_number'), ['id' => 'consignment_number', 'class' => 'form-control', 'maxlength' => '20']) !!}

            @if ($errors->has('consignment_number'))
            <span class="form-text">
                <strong>{{ $errors->first('consignment_number') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('additional_reference') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">Additional Reference:</label>

        <div class="col-sm-6">
            {!! Form::Text('additional_reference', old('additional_reference'), ['id' => 'additional_reference', 'class' => 'form-control', 'maxlength' => '30']) !!}

            @if ($errors->has('additional_reference'))
            <span class="form-text">
                <strong>{{ $errors->first('additional_reference') }}</strong>
            </span>
            @endif
        </div>
    </div>

    <div class="form-group row{{ $errors->has('number') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Entry Number:
        </label>

        <div class="col-sm-6">
            {!! Form::Text('number', old('number'), ['id' => 'number', 'class' => 'form-control', 'maxlength' => '11']) !!}

            @if ($errors->has('number'))
            <span class="form-text">
                <strong>{{ $errors->first('number') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('date') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Entry Date:
        </label>

        <div class="col-sm-6">
            {!! Form::select('date', dropDown('dates'), old('date', date('d-m-Y', strtotime('today'))), array('id' => 'date', 'class' => 'form-control')) !!} 

            @if ($errors->has('date'))
            <span class="form-text">
                <strong>{{ $errors->first('date') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('scs_job_number') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            SCS Job Number:
        </label>

        <div class="col-sm-6">
            {!! Form::Text('scs_job_number', old('scs_job_number'), ['id' => 'scs_job_number', 'class' => 'form-control', 'maxlength' => '14']) !!}

            @if ($errors->has('scs_job_number'))
            <span class="form-text">
                <strong>{{ $errors->first('scs_job_number') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('commercial_invoice_value') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Commercial Invoice Value:
        </label>

        <div class="col-sm-4">
            {!! Form::Text('commercial_invoice_value', old('commercial_invoice_value'), ['id' => 'commercial_invoice_value', 'class' => 'form-control decimal-only', 'maxlength' => '10']) !!}

            @if ($errors->has('commercial_invoice_value'))
            <span class="form-text">
                <strong>{{ $errors->first('commercial_invoice_value') }}</strong>
            </span>
            @endif

        </div>

        <div class="col-sm-2">                
            {!! Form::select('commercial_invoice_value_currency_code', dropDown('currencies'), null, array('id' => 'commercial_invoice_value_currency_code', 'class' => 'form-control')) !!}                
        </div>
    </div>

    <div class="form-group row{{ $errors->has('customs_value') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Customs Value (GBP):
        </label>

        <div class="col-sm-6">
            {!! Form::Text('customs_value', old('customs_value'), ['id' => 'customs_value', 'class' => 'form-control decimal-only', 'maxlength' => '10']) !!}

            @if ($errors->has('customs_value'))
            <span class="form-text">
                <strong>{{ $errors->first('customs_value') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('pieces') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Pieces:
        </label>

        <div class="col-sm-6">
            {!! Form::Text('pieces', old('pieces'), ['id' => 'pieces', 'class' => 'form-control numeric-only', 'maxlength' => '10']) !!}

            @if ($errors->has('pieces'))
            <span class="form-text">
                <strong>{{ $errors->first('pieces') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('weight') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Weight (KG):
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

    <div class="form-group row{{ $errors->has('commodity_count') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Commodity Count:
        </label>

        <div class="col-sm-6">
            {!! Form::Text('commodity_count', old('commodity_count'), ['id' => 'commodity_count', 'class' => 'form-control numeric-only', 'maxlength' => '2']) !!}

            @if ($errors->has('weight'))
            <span class="form-text">
                <strong>{{ $errors->first('commodity_count') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row buttons-main">
        <div class="col-sm-5">&nbsp;</div>
        <div class="col-sm-6">
            <a class="back btn btn-secondary" role="button">Cancel</a>
            <button type="submit" class="btn btn-primary">{{$submitButtonText}}</button>
        </div>
    </div>

</div>

