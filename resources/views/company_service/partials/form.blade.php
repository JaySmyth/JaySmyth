<h4>Leave field blank for default value</h4>
<div class="row">
    <div class="col-sm-6">

        {!! Form::hidden('company_id', $companyService->company_id) !!}
        {!! Form::hidden('service_id', $companyService->service_id) !!}
        <div class="form-group row{{ $errors->has('name') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Service Name: <abbr title="This information is optional.">*</abbr>
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

        <div class="form-group row{{ $errors->has('country_filter') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Country Filter: <abbr title="Format GB,IE to include or !GB,IE to exclude">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('country_filter', old('country_filter'), ['id' => 'country_filter', 'class' => 'form-control', 'maxlength' => '50', 'Placeholder' => 'eg. GB,IE to specify or !GB,IE to exclude']) !!}

                @if ($errors->has('country_filter'))
                    <span class="form-text">
                    <strong>{{ $errors->first('country_filter')}}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('monthly_limit') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Monthly Shipment Limit: <abbr title="This information is optional.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('monthly_limit', old('monthly_limit'), ['id' => 'monthly_limit', 'class' => 'form-control', 'maxlength' => 35]) !!}

                @if ($errors->has('monthly_limit'))
                    <span class="form-text">
                    <strong>{{ $errors->first('monthly_limit') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('max_weight_limit') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Max Weight limit:
            </label>

            <div class="col-sm-6">
                {!! Form::Text('max_weight_limit', old('max_weight_limit'), ['id' => 'max_weight_limit', 'class' => 'form-control', 'maxlength' => 35]) !!}

                @if ($errors->has('max_weight_limit'))
                    <span class="form-text">
                    <strong>{{ $errors->first('max_weight_limit') }}</strong>
                </span>
                @endif
            </div>
        </div>

        @include('partials.submit_buttons', ['submitButtonText' => $submitButtonText])

    </div>
</div>
