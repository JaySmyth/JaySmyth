<div class="row">
    <div class="col-sm-5">

        <div class="form-group row{{ $errors->has('code') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Code: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('code', old('code'), ['id' => 'code', 'class' => 'form-control', 'maxlength' => '10']) !!}

                @if ($errors->has('code'))
                <span class="form-text">
                    <strong>{{ $errors->first('code') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('name') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Name: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('name', old('name'), ['id' => 'name', 'class' => 'form-control', 'maxlength' => '50']) !!}

                @if ($errors->has('name'))
                <span class="form-text">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('carrier_code') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Carrier Code: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('carrier_code', old('carrier_code'), ['id' => 'carrier_code', 'class' => 'form-control', 'maxLength' => '10']) !!}

                @if ($errors->has('carrier_code'))
                <span class="form-text">
                    <strong>{{ $errors->first('carrier_code') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('carrier_name') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Carriers name for service: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('carrier_name', old('carrier_name'), ['id' => 'carrier_name', 'class' => 'form-control', 'maxlength' => '50']) !!}

                @if ($errors->has('carrier_name'))
                <span class="form-text">
                    <strong>{{ $errors->first('carrier_name') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('transend_route') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Transend Route: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('transend_route', old('transend_route'), ['id' => 'transend_route', 'class' => 'form-control', 'maxlength' => '5']) !!}

                @if ($errors->has('transend_route'))
                <span class="form-text">
                    <strong>{{ $errors->first('transend_route') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('account') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Account No: <abbr title="IFS account number with the carrier.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('account', old('account'), ['id' => 'account', 'class' => 'form-control', 'maxlength' => '12']) !!}

                @if ($errors->has('account'))
                <span class="form-text">
                    <strong>{{ $errors->first('account') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('scs_account_code') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                SCS Account: <abbr title="The SCS account no for this carrier.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('scs_account_code', old('scs_account_code'), ['id' => 'scs_account_code', 'class' => 'form-control numeric-only', 'maxlength' => '7']) !!}

                @if ($errors->has('scs_account_code'))
                <span class="form-text">
                    <strong>{{ $errors->first('scs_account_code') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('scs_job_route') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                SCS Job Route: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('scs_job_route', old('scs_job_route'), ['id' => 'scs_job_route', 'class' => 'form-control', 'maxlength' => '10']) !!}

                @if ($errors->has('scs_job_route'))
                <span class="form-text">
                    <strong>{{ $errors->first('scs_job_route') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('volumetric_divisor') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Volumetric Divisor: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('volumetric_divisor', old('volumetric_divisor'), ['id' => 'volumetric_divisor', 'class' => 'form-control numeric-only', 'maxlength' => '10']) !!}

                @if ($errors->has('volumetric_divisor'))
                <span class="form-text">
                    <strong>{{ $errors->first('volumetric_divisor') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('packaging_types') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Packaging Types: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('packaging_types', old('packaging_types'), ['id' => 'packaging_types', 'class' => 'form-control', 'maxlength' => '35']) !!}

                @if ($errors->has('packaging_types'))
                <span class="form-text">
                    <strong>{{ $errors->first('packaging_types') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('lithium_batteries') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Lithium Batteries: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::checkbox('lithium_batteries', 1, old('lithium_batteries'), ['id' => 'lithium_batteries']) !!}
            </div>
        </div>

        <div class="form-group row{{ $errors->has('max_customs_value') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Max Customs Value: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('max_customs_value', old('max_customs_value'), ['id' => 'max_customs_value', 'class' => 'form-control numeric-only', 'maxlength' => '10']) !!}

                @if ($errors->has('max_customs_value'))
                <span class="form-text">
                    <strong>{{ $errors->first('max_customs_value') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('allow_zero_cost') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Allow Zero Cost: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::checkbox('allow_zero_cost', 1, old('allow_zero_cost'), ['id' => 'allow_zero_cost']) !!}
            </div>
        </div>

        <div class="form-group row{{ $errors->has('default') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Default service: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::checkbox('default', 1, old('default'), ['id' => 'default']) !!}
            </div>
        </div>
    </div>

    <div class="col-sm-6">

        <div class="form-group row{{ $errors->has('sender_country_codes') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Sender Country Codes: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('sender_country_codes', old('sender_country_codes'), ['id' => 'sender_country_codes', 'class' => 'form-control', 'maxlength' => '100']) !!}

                @if ($errors->has('sender_country_codes'))
                <span class="form-text">
                    <strong>{{ $errors->first('sender_country_codes') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('recipient_country_codes') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Recipient Country Codes: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('recipient_country_codes', old('recipient_country_codes'), ['id' => 'recipient_country_codes', 'class' => 'form-control', 'maxlength' => '100']) !!}

                @if ($errors->has('recipient_country_codes'))
                <span class="form-text">
                    <strong>{{ $errors->first('recipient_country_codes') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('sender_postcode_regex') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Sender Postcode Regex: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('sender_postcode_regex', old('sender_postcode_regex'), ['id' => 'sender_postcode_regex', 'class' => 'form-control', 'maxlength' => '60']) !!}

                @if ($errors->has('sender_postcode_regex'))
                <span class="form-text">
                    <strong>{{ $errors->first('sender_postcode_regex') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('recipient_postcode_regex') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Recipient Postcode Regex: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('recipient_postcode_regex', old('recipient_postcode_regex'), ['id' => 'recipient_postcode_regex', 'class' => 'form-control', 'maxlength' => '60']) !!}

                @if ($errors->has('recipient_postcode_regex'))
                <span class="form-text">
                    <strong>{{ $errors->first('recipient_postcode_regex') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('carrier_name') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Carriers Acct no Regex: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('account_number_regex', old('account_number_regex'), ['id' => 'account_number_regex', 'class' => 'form-control', 'maxlength' => '60']) !!}

                @if ($errors->has('account_number_regex'))
                <span class="form-text">
                    <strong>{{ $errors->first('account_number_regex') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('min_weight') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Minimum Weight: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('min_weight', old('min_weight'), ['id' => 'min_weight', 'class' => 'form-control', 'maxlength' => '12']) !!}

                @if ($errors->has('min_weight'))
                <span class="form-text">
                    <strong>{{ $errors->first('min_weight') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('max_weight') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Maximum Weight: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('max_weight', old('max_weight'), ['id' => 'max_weight', 'class' => 'form-control', 'maxlength' => '12']) !!}

                @if ($errors->has('max_weight'))
                <span class="form-text">
                    <strong>{{ $errors->first('max_weight') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('max_pieces') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Maximum Pieces: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('max_pieces', old('max_pieces'), ['id' => 'max_pieces', 'class' => 'form-control', 'maxlength' => '12']) !!}

                @if ($errors->has('max_pieces'))
                <span class="form-text">
                    <strong>{{ $errors->first('max_pieces') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('max_dimension') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Maximum Dimension: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('max_dimension', old('max_dimension'), ['id' => 'max_dimension', 'class' => 'form-control', 'maxlength' => '12']) !!}

                @if ($errors->has('max_dimension'))
                <span class="form-text">
                    <strong>{{ $errors->first('max_dimension') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('max_girth') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Maximum Girth: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('max_girth', old('max_girth'), ['id' => 'max_girth', 'class' => 'form-control', 'maxlength' => '12']) !!}

                @if ($errors->has('max_girth'))
                <span class="form-text">
                    <strong>{{ $errors->first('max_girth') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('cost_rate_id') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Cost Rate: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('cost_rate_id', dropDown('costrates', 'Please select'), old('cost_rate_id'), array('id' => 'cost_rate_id', 'class' => 'form-control')) !!}

                @if ($errors->has('cost_rate_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('cost_rate_id') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('costs_surcharge_id') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Cost Surcharge Rate: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('costs_surcharge_id', dropDown('costSurcharges', 'Please select'), old('costs_surcharge_id'), array('id' => 'costs_surcharge_id', 'class' => 'form-control')) !!}

                @if ($errors->has('costs_surcharge_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('costs_surcharge_id') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('sales_rate_id') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Default Sales Rate: <abbr title="The Sales rate that will be used if no rate is set for the customer.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('sales_rate_id', dropDown('salesrates', 'Please select'), old('sales_rate_id'), array('id' => 'sales_rate_id', 'class' => 'form-control')) !!}

                @if ($errors->has('sales_rate_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('sales_rate_id') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('sales_surcharge_id') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Default Surcharge Rate: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('sales_surcharge_id', dropDown('salesSurcharges', 'Please select'), old('sales_surcharge_id'), array('id' => 'sales_surcharge_id', 'class' => 'form-control')) !!}

                @if ($errors->has('sales_surcharge_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('sales_surcharge_id') }}</strong>
                </span>
                @endif

            </div>
        </div>

    </div>
</div>

<div class="row">
        <div class="col-sm-5">&nbsp;</div>
        <div class="col-sm-6">
            <a class="back btn btn-secondary" role="button">Cancel</a>
            <button enabled="submit" class="btn btn-primary ml-sm-4">{{ $submitButtonText }}</button>
        </div>
</div>
