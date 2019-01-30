<div class="row">

    <div class="col-6">

        <div class="form-group row{{ $errors->has('company_name') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Company Name: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('company_name', old('company_name'), ['id' => 'company_name', 'class' => 'form-control', 'maxlength' => '200']) !!}

                @if ($errors->has('company_name'))
                <span class="form-text">
                    <strong>{{ $errors->first('company_name') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('contact') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">Contact: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::Text('contact', old('contact'), ['id' => 'contact', 'class' => 'form-control', 'maxlength' => '100']) !!}

                @if ($errors->has('contact'))
                <span class="form-text">
                    <strong>{{ $errors->first('contact') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('telephone') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">Telephone: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::Text('telephone', old('telephone'), ['id' => 'telephone', 'class' => 'form-control', 'maxlength' => '100']) !!}

                @if ($errors->has('telephone'))
                <span class="form-text">
                    <strong>{{ $errors->first('telephone') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('email') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">Email: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::Text('email', old('email'), ['id' => 'email', 'class' => 'form-control', 'maxlength' => '100']) !!}

                @if ($errors->has('email'))
                <span class="form-text">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('sale_id') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">Account Manager: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::select('sale_id', dropDown('sales'), old('sale_id', 'GB'), array('class' => 'form-control')) !!}

                @if ($errors->has('sale_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('sale_id') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('department_id') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">Department: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::select('department_id', dropDown('departments', 'Please select'), old('department_id'), array('class' => 'form-control')) !!}

                @if ($errors->has('department_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('department_id') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('from_city') ? ' has-danger' : '' }}">
            <label class="col-sm-5 col-form-label">From City: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::Text('from_city', old('from_city'), ['id' => 'from_city', 'class' => 'form-control', 'maxlength' => '100']) !!}

                @if ($errors->has('from_city'))
                <span class="form-text">
                    <strong>{{ $errors->first('from_city') }}</strong>
                </span>
                @endif
            </div>

        </div>

        <div class="form-group row{{ $errors->has('from_country_code') ? ' has-danger' : '' }}">
            <label class="col-sm-5 col-form-label">From Country: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::select('from_country_code', dropDown('countries'), old('from_country_code', 'GB'), array('id' => 'from_country_code', 'class' => 'form-control')) !!}

                @if ($errors->has('from_country_code'))
                <span class="form-text">
                    <strong>{{ $errors->first('from_country_code') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('to_city') ? ' has-danger' : '' }}">
            <label class="col-sm-5 col-form-label">To City: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::Text('to_city', old('to_city'), ['id' => 'to_city', 'class' => 'form-control', 'maxlength' => '100']) !!}

                @if ($errors->has('to_city'))
                <span class="form-text">
                    <strong>{{ $errors->first('to_city') }}</strong>
                </span>
                @endif
            </div>
        </div>


        <div class="form-group row{{ $errors->has('to_country_code') ? ' has-danger' : '' }}">
            <label class="col-sm-5 col-form-label">To Country: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::select('to_country_code', dropDown('countries'), old('to_country_code', 'GB'), array('id' => 'to_country_code', 'class' => 'form-control')) !!}

                @if ($errors->has('to_country_code'))
                <span class="form-text">
                    <strong>{{ $errors->first('to_country_code') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('information') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Information: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                <textarea name="information" id="information" rows="4" maxlength="255" class="form-control">@if(old('information')){{ old('information')}}@elseif(isset($quotation)){{$quotation->information}}@endif</textarea>

                @if ($errors->has('information'))
                <span class="form-text">
                    <strong>{{ $errors->first('information') }}</strong>
                </span>
                @endif

            </div>
        </div>

    </div>

    
    <div class="col-6">


        <div class="form-group row{{ $errors->has('pieces') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">Pieces: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::Text('pieces', old('pieces'), ['id' => 'pieces', 'class' => 'form-control numeric-only', 'maxlength' => '7']) !!}

                @if ($errors->has('pieces'))
                <span class="form-text">
                    <strong>{{ $errors->first('pieces') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('weight') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">Weight (kg): <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::Text('weight', old('weight'), ['id' => 'weight', 'class' => 'form-control decimal-only', 'maxlength' => '7']) !!}

                @if ($errors->has('weight'))
                <span class="form-text">
                    <strong>{{ $errors->first('weight') }}</strong>
                </span>
                @endif

            </div>
        </div>


        <div class="form-group row{{ $errors->has('volumetric_weight') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">Volumetric Weight (kg):</label>

            <div class="col-sm-6">
                {!! Form::Text('volumetric_weight', old('volumetric_weight'), ['id' => 'volumetric_weight', 'class' => 'form-control decimal-only', 'maxlength' => '10']) !!}

                @if ($errors->has('volumetric_weight'))
                <span class="form-text">
                    <strong>{{ $errors->first('volumetric_weight') }}</strong>
                </span>
                @endif

            </div>
        </div>


        <div class="form-group row{{ $errors->has('dimensions') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Dimensions (cm): <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('dimensions', old('dimensions'), ['id' => 'dimensions', 'class' => 'form-control', 'maxlength' => '255']) !!}

                @if ($errors->has('dimensions'))
                <span class="form-text">
                    <strong>{{ $errors->first('dimensions') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('goods_description') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Goods Description: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('goods_description', old('goods_description'), ['id' => 'goods_description', 'class' => 'form-control', 'maxlength' => '100']) !!}

                @if ($errors->has('goods_description'))
                <span class="form-text">
                    <strong>{{ $errors->first('goods_description') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('special_requirements') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Special Requirements:
            </label>

            <div class="col-sm-6">
                {!! Form::Text('special_requirements', old('special_requirements'), ['id' => 'special_requirements', 'class' => 'form-control', 'maxlength' => '255']) !!}

                @if ($errors->has('special_requirements'))
                <span class="form-text">
                    <strong>{{ $errors->first('special_requirements') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('quote') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">Quote (excluding VAT): <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-4">
                {!! Form::Text('quote', old('quote'), ['id' => 'quote', 'class' => 'form-control decimal-only', 'maxlength' => '10']) !!}

                @if ($errors->has('quote'))
                <span class="form-text">
                    <strong>{{ $errors->first('quote') }}</strong>
                </span>
                @endif

            </div>

            <div class="col-sm-2">
                {!! Form::select('currency_code', dropDown('currencies'), null, array('id' => 'currency_code', 'class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group row{{ $errors->has('rate_of_exchange') ? ' has-danger' : '' }}">
            <label class="col-sm-5 col-form-label">Rate of Exchange: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::Text('rate_of_exchange', old('rate_of_exchange'), ['id' => 'rate_of_exchange', 'class' => 'form-control decimal-only', 'maxlength' => '10']) !!}

                @if ($errors->has('rate_of_exchange'))
                <span class="form-text">
                    <strong>{{ $errors->first('rate_of_exchange') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('terms') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Subject To:
            </label>

            <div class="col-sm-6">
                {!! Form::Text('terms', old('terms'), ['id' => 'terms', 'class' => 'form-control', 'maxlength' => '255']) !!}

                @if ($errors->has('terms'))
                <span class="form-text">
                    <strong>{{ $errors->first('terms') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('valid_to') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">Valid To: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::select('valid_to', dropDown('datesShort'), old('valid_to'), array('id' => 'valid_to', 'class' => 'form-control')) !!}

                @if ($errors->has('valid_to'))
                <span class="form-text">
                    <strong>{{ $errors->first('valid_to') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('comments') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Internal Comments:
            </label>

            <div class="col-sm-6">
                <textarea name="comments" id="comments" rows="4" maxlength="255" class="form-control">@if(old('comments')){{ old('comments')}}@elseif(isset($quotation)){{$quotation->comments}}@endif</textarea>

                @if ($errors->has('dimensions'))
                <span class="form-text">
                    <strong>{{ $errors->first('comments') }}</strong>
                </span>
                @endif

            </div>
        </div>

    </div>

    @include('partials.submit_buttons', ['submitButtonText' => $submitButtonText])

</div>


