<div class="row">

    <div class="col-sm-6">

        <div class="form-group row{{ $errors->has('vendor') ? ' has-danger' : '' }}">  

            <label class="col-sm-3  col-form-label">Vendor: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-5">
                {!! Form::Text('vendor', old('vendor'), ['id' => 'vendor', 'class' => 'form-control', 'maxlength' => '150']) !!}

                @if ($errors->has('vendor'))
                <span class="form-text">
                    <strong>{{ $errors->first('vendor') }}</strong>
                </span>
                @endif
            </div>

        </div>

        <div class="form-group row{{ $errors->has('commodity_code') ? ' has-danger' : '' }}">  

            <label class="col-sm-3  col-form-label">Commodity Code: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-5">
                {!! Form::Text('commodity_code', old('commodity_code'), ['id' => 'commodity_code', 'class' => 'form-control', 'maxlength' => '15']) !!}

                @if ($errors->has('commodity_code'))
                <span class="form-text">
                    <strong>{{ $errors->first('commodity_code') }}</strong>
                </span>
                @endif
            </div>

        </div>

        <div class="form-group row{{ $errors->has('country_of_origin') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                Country Of Origin:
            </label>

            <div class="col-sm-5">
                {!! Form::select('country_of_origin', dropDown('countries', 'Not specified'), old('country_of_origin'), array('id' => 'country_of_origin', 'class' => 'form-control')) !!} 

                @if ($errors->has('country_of_origin'))
                <span class="form-text">
                    <strong>{{ $errors->first('country_of_origin') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('value') ? ' has-danger' : '' }}">  

            <label class="col-sm-3  col-form-label">Item Value: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-5">
                {!! Form::Text('value', old('value'), ['id' => 'value', 'class' => 'form-control decimal-only', 'maxlength' => '15']) !!}

                @if ($errors->has('value'))
                <span class="form-text">
                    <strong>{{ $errors->first('value') }}</strong>
                </span>
                @endif
            </div>

        </div>

        <div class="form-group row{{ $errors->has('duty') ? ' has-danger' : '' }}">  

            <label class="col-sm-3  col-form-label">Duty: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-5">
                {!! Form::Text('duty', old('duty'), ['id' => 'duty', 'class' => 'form-control decimal-only', 'maxlength' => '15']) !!}

                @if ($errors->has('duty'))
                <span class="form-text">
                    <strong>{{ $errors->first('duty') }}</strong>
                </span>
                @endif
            </div>

        </div>

        <div class="form-group row{{ $errors->has('duty_percent') ? ' has-danger' : '' }}">  

            <label class="col-sm-3  col-form-label">Duty Percent: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-5">
                {!! Form::Text('duty_percent', old('duty_percent'), ['id' => 'duty_percent', 'class' => 'form-control decimal-only', 'maxlength' => '15']) !!}

                @if ($errors->has('duty_percent'))
                <span class="form-text">
                    <strong>{{ $errors->first('duty_percent') }}</strong>
                </span>
                @endif
            </div>

        </div>

        <div class="form-group row{{ $errors->has('vat') ? ' has-danger' : '' }}">  

            <label class="col-sm-3  col-form-label">VAT: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-5">
                {!! Form::Text('vat', old('vat'), ['id' => 'vat', 'class' => 'form-control decimal-only', 'maxlength' => '15']) !!}

                @if ($errors->has('vat'))
                <span class="form-text">
                    <strong>{{ $errors->first('vat') }}</strong>
                </span>
                @endif
            </div>

        </div>

        <div class="form-group row{{ $errors->has('weight') ? ' has-danger' : '' }}">  

            <label class="col-sm-3  col-form-label">Nett Weight (KG): <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-5">
                {!! Form::Text('weight', old('weight'), ['id' => 'weight', 'class' => 'form-control decimal-only', 'maxlength' => '15']) !!}

                @if ($errors->has('weight'))
                <span class="form-text">
                    <strong>{{ $errors->first('weight') }}</strong>
                </span>
                @endif
            </div>

        </div>

        <div class="form-group row">          
            <label class="col-sm-3  col-form-label">
                CPC: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-5">
                {!! Form::select('customs_procedure_code_id', dropDown('cpc'), old('customs_procedure_code_id'), array('id' => 'customs_procedure_code_id', 'class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group row buttons-main">
            <div class="col-sm-3">&nbsp;</div>
            <div class="col-sm-9">
                <a class="back btn btn-secondary" role="button">Cancel</a>
                <button type="submit" class="btn btn-primary">{{$submitButtonText}}</button>
            </div>
        </div>
    </div>
</div>