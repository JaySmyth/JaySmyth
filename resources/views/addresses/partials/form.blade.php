{!! Form::hidden('definition', Request::get('definition')) !!}
<div class="row">
    <div class="col-sm-6">

        @if(Auth::user()->hasMultipleCompanies())

        <div class="form-group row{{ $errors->has('company_id') ? ' has-danger' : '' }}">

            <label class="col-sm-3  col-form-label">
                Shipper: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
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

        <div class="form-group row{{ $errors->has('name') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                Name: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('name', old('name'), ['id' => 'name', 'class' => 'form-control', 'maxlength' => 35]) !!}

                @if ($errors->has('name'))
                <span class="form-text">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('company_name') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                Company Name:
            </label>

            <div class="col-sm-7">
                {!! Form::Text('company_name', old('company_name'), ['id' => 'company_name', 'class' => 'form-control', 'maxlength' => 35]) !!}

                @if ($errors->has('company_name'))
                <span class="form-text">
                    <strong>{{ $errors->first('company_name') }}</strong>
                </span>
                @endif

            </div>
        </div>


        <div class="form-group row{{ $errors->has('type') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                Address Type: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('type',dropDown('type'), old('type'), array('id' => 'type', 'class' => 'form-control')) !!}

                @if ($errors->has('type'))
                <span class="form-text">
                    <strong>{{ $errors->first('type') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('address1') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                Address 1: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('address1', old('address1'), ['id' => 'address1', 'class' => 'form-control', 'maxlength' => 35]) !!}

                @if ($errors->has('address1'))
                <span class="form-text">
                    <strong>{{ $errors->first('address1') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('address2') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                Address 2:
            </label>

            <div class="col-sm-7">
                {!! Form::Text('address2', old('address2'), ['id' => 'address2', 'class' => 'form-control', 'maxlength' => 35]) !!}
            </div>
        </div>

        <div class="form-group row{{ $errors->has('address3') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                Address 3:
            </label>

            <div class="col-sm-7">
                {!! Form::Text('address3', old('address3'), ['id' => 'address3', 'class' => 'form-control', 'maxlength' => 35]) !!}
            </div>
        </div>

        <div class="form-group row{{ $errors->has('city') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                City: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('city', old('city'), ['id' => 'city', 'class' => 'form-control', 'maxlength' => 35]) !!}

                @if ($errors->has('city'))
                <span class="form-text">
                    <strong>{{ $errors->first('city') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('state') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                State/ County:
            </label>

            <div class="col-sm-7">
                {!! Form::Text('state', old('state'), ['id' => 'state', 'class' => 'form-control', 'maxlength' => 35]) !!}

                @if ($errors->has('state'))
                <span class="form-text">
                    <strong>{{ $errors->first('state') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('postcode') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                Postcode: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('postcode', old('postcode'), ['id' => 'postcode', 'class' => 'form-control', 'maxlength' => '9']) !!}

                @if ($errors->has('postcode'))
                <span class="form-text">
                    <strong>{{ $errors->first('postcode') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('country_code') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                Country: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('country_code', dropDown('countries'), old('country_code', 'GB'), array('id' => 'country_code', 'class' => 'form-control')) !!}

                @if ($errors->has('country_code'))
                <span class="form-text">
                    <strong>{{ $errors->first('country_code') }}</strong>
                </span>
                @endif

            </div>
        </div>


        <div class="form-group row{{ $errors->has('telephone') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                Telephone: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('telephone', old('telephone'), ['id' => 'telephone', 'class' => 'form-control', 'maxlength' => 35]) !!}

                @if ($errors->has('telephone'))
                <span class="form-text">
                    <strong>{{ $errors->first('telephone') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('email') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                Email:
            </label>

            <div class="col-sm-7">
                {!! Form::Text('email', old('email'), ['id' => 'email', 'class' => 'form-control', 'maxlength' => '50']) !!}

                @if ($errors->has('email'))
                <span class="form-text">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('account_number') ? ' has-danger' : '' }}">
            <label class="col-sm-3 col-form-label">
                Account Number:
            </label>

            <div class="col-sm-7">
                {!! Form::Text('account_number', old('account_number'), ['id' => 'account_number', 'class' => 'form-control', 'maxlength' => '15']) !!}

                @if ($errors->has('account_number'))
                <span class="form-text">
                    <strong>{{ $errors->first('account_number') }}</strong>
                </span>
                @endif

            </div>
        </div>

        @include('partials.submit_buttons', ['submitButtonText' => $submitButtonText])

    </div>

</div>
