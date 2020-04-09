<div class="row">
    <div class="col-sm-6">

        <input type="hidden" name="charge_id" value="{{$surcharge->id}}">
        <input type="hidden" name="surcharge_id" value="{{$surcharge->surcharge_id}}">
        <input type="hidden" name="company_id" value="{{$surcharge->company_id}}">

        <div class="form-group row{{ $errors->has('code') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Surcharge Group:
            </label>

            <div class="col-sm-6">
                {{$surcharge->name}}
            </div>
        </div>

        <div class="form-group row{{ $errors->has('code') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Applies To: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                @if($submitButtonText == "Update Surcharge")
                    @if($surcharge->company_id > 0)
                        {{App\Models\Company::find($surcharge->company_id)->company_name}}
                    @else
                        All Companies
                    @endif
                @else
                    {!! Form::select('company_id',dropDown('companies'), old('company_id'), ['id' => 'company_id', 'class' => 'form-control']) !!}
                @endif

                @if ($errors->has('code'))
                    <span class="form-text">
                    <strong>{{ $errors->first('code')}}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('code') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                IFS Service Code: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">

                @if($submitButtonText == "Update Surcharge")
                {!! Form::Text('code', old('code'), ['id' => 'code', 'class' => 'form-control', 'readonly' => 'true']) !!}
                @else
                {!! Form::select('code',dropDown('ifsChargeCodes'), old('code'), ['id' => 'code', 'class' => 'form-control']) !!}
                @endif

                @if ($errors->has('code'))
                <span class="form-text">
                    <strong>{{ $errors->first('code')}}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('weight_rate') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Weight Rate: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('weight_rate', old('weight_rate', $surcharge->weight_rate ?? 0), ['id' => 'weight_rate', 'class' => 'form-control', 'maxlength' => 10]) !!}

                @if ($errors->has('weight_rate'))
                <span class="form-text">
                    <strong>{{ $errors->first('weight_rate') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('package_rate') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Package Rate:
            </label>

            <div class="col-sm-6">
                {!! Form::Text('package_rate', old('package_rate', $surcharge->package_rate ?? 0), ['id' => 'package_rate', 'class' => 'form-control', 'maxlength' => 10]) !!}
            </div>
        </div>

        <div class="form-group row{{ $errors->has('consignment_rate') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Consignment_rate:
            </label>

            <div class="col-sm-6">
                {!! Form::Text('consignment_rate', old('consignment_rate', $surcharge->consignment_rate ?? 0), ['id' => 'consignment_rate', 'class' => 'form-control', 'maxlength' => 10]) !!}
            </div>
        </div>

        <div class="form-group row{{ $errors->has('min') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Minimum Charge: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('min', old('min', $surcharge->min ?? 0), ['id' => 'min', 'class' => 'form-control', 'maxlength' => 10]) !!}

                @if ($errors->has('min'))
                <span class="form-text">
                    <strong>{{ $errors->first('min') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('from_date') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Date From:
            </label>

            <div class="col-sm-6">
                {!! Form::select('from_date', dropdown('datesFuture'), old('from_date', date('d-m-Y', strtotime('today'))), ['id' => 'from_date', 'class' => 'form-control']) !!}

                @if ($errors->has('from_date'))
                <span class="form-text">
                    <strong>{{ $errors->first('from_date') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('to_date') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Date To:
            </label>

            <div class="col-sm-6">
                {!! Form::Text('to_date', '31-12-2099', ['id' => 'to_date', 'class' => 'form-control', 'readonly']) !!}

                @if ($errors->has('to_date'))
                <span class="form-text">
                    <strong>{{ $errors->first('to_date') }}</strong>
                </span>
                @endif

            </div>
        </div>

        @include('partials.submit_buttons', ['submitButtonText' => $submitButtonText])

    </div>

</div>
