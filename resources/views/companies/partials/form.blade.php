<div class="row">
    <div class="col-sm-6">

        <div class="form-group row{{ $errors->has('company_name') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Company Name: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('company_name', old('company_name'), ['id' => 'company_name', 'class' => 'form-control', 'maxlength' => '50']) !!}

                @if ($errors->has('company_name'))
                <span class="form-text">
                    <strong>{{ $errors->first('company_name') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('address_type') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Address Type: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('address_type',dropDown('type'), old('address_type'), array('id' => 'address_type', 'class' => 'form-control')) !!}

                @if ($errors->has('address_type'))
                <span class="form-text">
                    <strong>{{ $errors->first('address_type')}}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('address1') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Address 1: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('address1', old('address1'), ['id' => 'address1', 'class' => 'form-control', 'maxlength' => 35]) !!}

                @if ($errors->has('address1'))
                <span class="form-text">
                    <strong>{{ $errors->first('address1') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('address2') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Address 2:
            </label>

            <div class="col-sm-6">
                {!! Form::Text('address2', old('address2'), ['id' => 'address2', 'class' => 'form-control', 'maxlength' => 35]) !!}
            </div>
        </div>

        <div class="form-group row{{ $errors->has('address3') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Address 3:
            </label>

            <div class="col-sm-6">
                {!! Form::Text('address3', old('address3'), ['id' => 'address3', 'class' => 'form-control', 'maxlength' => 35]) !!}
            </div>
        </div>

        <div class="form-group row{{ $errors->has('city') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                City: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('city', old('city'), ['id' => 'city', 'class' => 'form-control', 'maxlength' => 35]) !!}

                @if ($errors->has('city'))
                <span class="form-text">
                    <strong>{{ $errors->first('city') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('state') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                State: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('state', old('state'), ['id' => 'state', 'class' => 'form-control', 'maxlength' => 35]) !!}

                @if ($errors->has('state'))
                <span class="form-text">
                    <strong>{{ $errors->first('state') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('postcode') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Postcode: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('postcode', old('postcode'), ['id' => 'postcode', 'class' => 'form-control', 'maxlength' => '9']) !!}

                @if ($errors->has('postcode'))
                <span class="form-text">
                    <strong>{{ $errors->first('postcode') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('country_code') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Country: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('country_code', dropDown('countries'), old('country_code', 'GB'), array('id' => 'country_code', 'class' => 'form-control')) !!}

                @if ($errors->has('country_code'))
                <span class="form-text">
                    <strong>{{ $errors->first('country_code') }}</strong>
                </span>
                @endif

            </div>
        </div>
        <div class="form-group row{{ $errors->has('telephone') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Telephone: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('telephone', old('telephone'), ['id' => 'telephone', 'class' => 'form-control', 'maxlength' => '15']) !!}

                @if ($errors->has('telephone'))
                <span class="form-text">
                    <strong>{{ $errors->first('telephone') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('email') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Email:
            </label>

            <div class="col-sm-6">
                {!! Form::Text('email', old('email'), ['id' => 'email', 'class' => 'form-control', 'maxlength' => '50']) !!}

                @if ($errors->has('email'))
                <span class="form-text">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif

            </div>
        </div>
        <div class="form-group row{{ $errors->has('shipper_type_override') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Shipper Address Override: <abbr title="Usually Customer Supplied. May be used to overide Customer supplied value if Customer consistently misdeclares address type">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('shipper_type_override',dropDown('type', 'Customer Supplied'), old('shipper_type_override'), array('id' => 'shipper_type_override', 'class' => 'form-control')) !!}

                @if ($errors->has('shipper_type_override'))
                <span class="form-text">
                    <strong>{{ $errors->first('shipper_type_override')}}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('recipient_type_override') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Recipient Address Override: <abbr title="Usually Customer Supplied">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('recipient_type_override',dropDown('type', 'Customer Supplied'), old('recipient_type_override'), array('id' => 'recipient_type_override', 'class' => 'form-control')) !!}

                @if ($errors->has('recipient_type_override'))
                <span class="form-text">
                    <strong>{{ $errors->first('recipient_type_override')}}</strong>
                </span>
                @endif

            </div>
        </div>

    </div>


    <div class="col-sm-6">

        <div class="form-group row{{ $errors->has('site_name') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">Site Name: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::Text('site_name', old('site_name'), ['id' => 'site_name', 'class' => 'form-control', 'maxlength' => 35]) !!}

                @if ($errors->has('site_name'))
                <span class="form-text">
                    <strong>{{ $errors->first('site_name') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('depot_id') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Depot: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('depot_id', dropDown('depots'), old('depot_id'), array('id' => 'depot_id', 'class' => 'form-control')) !!}

                @if ($errors->has('depot_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('depot_id') }}</strong>
                </span>
                @endif

            </div>
        </div>

        @if(!Request::is('companies/*/edit'))
        <div class="form-group row">
            <label class="col-sm-4  col-form-label">
                Account Status: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('enabled', dropDown('enabled'), old('enabled'), array('id' => 'enabled', 'class' => 'form-control')) !!}
            </div>
        </div>
        @endif

        <div class="form-group row">
            <label class="col-sm-4  col-form-label">
                Shipping Mode: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('testing', dropDown('testing'), old('testing'), array('id' => 'testing', 'class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group row{{ $errors->has('notes') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Notes:
            </label>

            <div class="col-sm-6">
                {!! Form::Text('notes', old('notes'), ['id' => 'notes', 'class' => 'form-control', 'maxlength' => '255']) !!}

                @if ($errors->has('notes'))
                <span class="form-text">
                    <strong>{{ $errors->first('notes') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row">&nbsp;</div>

        <div class="form-group row">
            <label class="col-sm-4  col-form-label">
                Label Size: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('print_format_id', dropDown('printFormats'), old('print_format_id'), array('id' => 'print_format_id', 'class' => 'form-control')) !!}
            </div>
        </div>

        @if(Auth::user()->hasRole('ifsa'))
        <div class="form-group row">
            <label class="col-sm-4  col-form-label">
                Master Label Req: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('master_label', dropDown('boolean'), old('master_label'), array('id' => 'master_label', 'class' => 'form-control')) !!}
            </div>
        </div>
        @endif

        <div class="form-group row">
            <label class="col-sm-4  col-form-label">
                Commercial Invoice Req: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('commercial_invoice', dropDown('boolean'), old('commercial_invoice'), array('id' => 'commercial_invoice', 'class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4  col-form-label">
                Localisation: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('localisation_id', dropDown('localisations'), old('localisation_id'), array('id' => 'localisation_id', 'class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group row">&nbsp;</div>

        <div class="form-group row{{ $errors->has('scs_code') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">SCS Code: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::Text('scs_code', old('scs_code'), ['id' => 'scs_code', 'class' => 'form-control numeric-only', 'maxlength' => '7']) !!}

                @if ($errors->has('scs_code'))
                <span class="form-text">
                    <strong>{{ $errors->first('scs_code') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('group_account') ? ' has-danger' : '' }}">
            <label class="col-sm-4 col-form-label">Group Account:</label>

            <div class="col-sm-6">
                {!! Form::Text('group_account', old('group_account'), ['id' => 'group_account', 'class' => 'form-control', 'maxlength' => '10']) !!}

                @if ($errors->has('group_account'))
                <span class="form-text">
                    <strong>{{ $errors->first('group_account') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4  col-form-label">
                VAT Exempt: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('vat_exempt', array(0 => 'No', 1 => 'Yes'), old('vat_exempt'), array('id' => 'vat_exempt', 'class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4  col-form-label">
                Salesperson: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('sale_id', dropDown('sales'), old('sale_id'), array('id' => 'sale_id', 'class' => 'form-control')) !!}
            </div>
        </div>

        @if(Auth::user()->hasRole('ifsa'))
        <div class="form-group row">
            <label class="col-sm-4  col-form-label">
                Carrier Choice: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('carrier_choice', dropDown('carrierChoice'), old('carrier_choice'), array('id' => 'carrier_choice', 'class' => 'form-control')) !!}
            </div>
        </div>
        @endif

    </div>

    @include('partials.submit_buttons', ['submitButtonText' => $submitButtonText])

</div>
