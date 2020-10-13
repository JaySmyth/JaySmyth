<p class="text-large text-center text-danger">It is your legal responsibility to provide correct commodity information. Please contact IFS if you require assistance.</p>
<p class="text-large text-center text-danger font-weight-bold">You must provide valid commodity details to prevent your shipment being held by customs!</p>
<p class="text-large text-center mb-4">See <a href="https://www.gov.uk/trade-tariff/sections" target="_blank">https://www.gov.uk/trade-tariff/sections</a> for correct commodity code and <a href="https://hts.usitc.gov/" target="_blank">https://hts.usitc.gov/</a> for correct harmonized code.</p>


<div class="row">
    <div class="col-sm-6">

        @if(Auth::user()->hasMultipleCompanies())

        <div class="form-group row{{ $errors->has('company_id') ? ' has-danger' : '' }}">

            <label class="col-sm-4  col-form-label">
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

        <div class="form-group row{{ $errors->has('description') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Description: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('description', old('description'), ['id' => 'description', 'class' => 'form-control', 'maxlength' => '100']) !!}

                @if ($errors->has('description'))
                <span class="form-text">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('product_code') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Product Code:
            </label>

            <div class="col-sm-7">
                {!! Form::Text('product_code', old('product_code'), ['id' => 'product_code', 'class' => 'form-control', 'maxlength' => '50']) !!}

                @if ($errors->has('product_code'))
                <span class="form-text">
                    <strong>{{ $errors->first('product_code') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('manufacturer') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Manufacturer:
            </label>

            <div class="col-sm-7">
                {!! Form::Text('manufacturer', old('manufacturer'), ['id' => 'manufacturer', 'class' => 'form-control', 'maxlength' => '100']) !!}

                @if ($errors->has('manufacturer'))
                <span class="form-text">
                    <strong>{{ $errors->first('manufacturer') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('country_of_manufacture') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Country Of Manufacture: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('country_of_manufacture', dropDown('countries'), old('country_of_manufacture', 'GB'), array('id' => 'country_of_manufacture', 'class' => 'form-control')) !!}

                @if ($errors->has('country_of_manufacture'))
                <span class="form-text">
                    <strong>{{ $errors->first('country_of_manufacture') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('commodity_code') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Commodity Code:
            </label>

            <div class="col-sm-7">
                {!! Form::Text('commodity_code', old('commodity_code'), ['id' => 'commodity_code', 'class' => 'form-control', 'maxlength' => '12']) !!}

                @if ($errors->has('commodity_code'))
                <span class="form-text">
                    <strong>{{ $errors->first('commodity_code') }}</strong>
                </span>
                @endif

            </div>
        </div>

    </div>

    <div class="col-sm-6">

        <div class="form-group row{{ $errors->has('harmonized_code') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Harmonized Code: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('harmonized_code', old('harmonized_code'), ['id' => 'harmonized_code', 'class' => 'form-control', 'maxlength' => '15']) !!}

                @if ($errors->has('harmonized_code'))
                <span class="form-text">
                    <strong>{{ $errors->first('harmonized_code') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('unit_value') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Value: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-4">
                {!! Form::Text('unit_value', null, ['id' => 'commodity_unit_value','class' => 'form-control decimal-only', 'maxlength' => '8']) !!}

                @if ($errors->has('unit_value'))
                <span class="form-text">
                    <strong>{{ $errors->first('unit_value') }}</strong>
                </span>
                @endif

            </div>
            <div class="col-sm-3">
                {!! Form::select('currency_code', dropDown('currencies'), null, array('id' => 'commodity_currency_code', 'class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group row{{ $errors->has('uom') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Unit Of Measure: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('uom', dropDown('uoms', 'Please select'), null, array('id' => 'uom', 'class' => 'form-control')) !!}

                @if ($errors->has('uom'))
                <span class="form-text">
                    <strong>{{ $errors->first('uom') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('unit_weight') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Weight: <abbr title="This information is required.">*</abbr>
            </label>
            <div class="col-sm-4">
                {!! Form::Text('unit_weight', null, ['id' => 'commodity_unit_weight','class' => 'form-control decimal-only', 'maxlength' => '8']) !!}

                @if ($errors->has('unit_weight'))
                <span class="form-text">
                    <strong>{{ $errors->first('unit_weight') }}</strong>
                </span>
                @endif

            </div>
            <div class="col-sm-3">
                {!! Form::select('weight_uom', dropDown('weightUom'), null, array('id' => 'commodity_weight_uom', 'class' => 'form-control')) !!}
            </div>
        </div>

        <!--
        
        <div class="form-group row{{ $errors->has('length') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Length:
            </label>

            <div class="col-sm-7">
                <div class="input-group">
                    {!! Form::Text('length', null, ['id' => 'commodity_length','class' => 'form-control numeric-only', 'maxlength' => '3']) !!}
                    <div class="input-group-append form-control-sm">cm</div>
                </div>

                @if ($errors->has('length'))
                <span class="form-text">
                    <strong>{{ $errors->first('length') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('width') ? ' has-danger' : '' }}">
            <label class="col-sm-4 col-form-label">
                Width:
            </label>

            <div class="col-sm-7">
                <div class="input-group">
                    {!! Form::Text('width', null, ['id' => 'commodity_width','class' => 'form-control numeric-only', 'maxlength' => '3']) !!}
                    <div class="input-group-append form-control-sm">cm</div>
                </div>

                @if ($errors->has('width'))
                <span class="form-text">
                    <strong>{{ $errors->first('width') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('height') ? ' has-danger' : '' }}">
            <label class="col-sm-4 col-form-label">
                Height:
            </label>

            <div class="col-sm-7">
                <div class="input-group">
                    {!! Form::Text('height', null, ['id' => 'commodity_height','class' => 'form-control numeric-only', 'maxlength' => '3']) !!}
                    <div class="input-group-append form-control-sm">cm</div>
                </div>

                @if ($errors->has('height'))
                <span class="form-text">
                    <strong>{{ $errors->first('height') }}</strong>
                </span>
                @endif

            </div>
        </div>
        
        -->

    </div>

    @include('partials.submit_buttons', ['submitButtonText' => $submitButtonText])

</div>