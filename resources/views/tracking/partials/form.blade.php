{!! Form::hidden('shipment_id', $shipment->id) !!}

<div class="row">

    <div class="col-sm-6">

        <div class="form-group row{{ $errors->has('country_code') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Status: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('status_code', dropDown('statusCodes', 'Please select'), old('status_code', $shipment->status->code), array('id' => 'status_code', 'class' => 'form-control')) !!}

                @if ($errors->has('status_code'))
                <span class="form-text">
                    <strong>{{ $errors->first('status_code') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('date') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Date / Time:
            </label>

            <div class="col-sm-3">
                {!! Form::select('date', dropDown('dates'), old('date', date('d-m-Y', strtotime('today'))), array('id' => 'date', 'class' => 'form-control')) !!}

                @if ($errors->has('date'))
                <span class="form-text">
                    <strong>{{ $errors->first('date') }}</strong>
                </span>
                @endif

            </div>

            <div class="col-sm-3">
                {!! Form::select('time', dropDown('times'), old('time', date('H:i')), array('id' => 'time', 'class' => 'form-control')) !!}

                @if ($errors->has('time'))
                <span class="form-text">
                    <strong>{{ $errors->first('time') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('city') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                City: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('city', old('city'), ['id' => 'city', 'class' => 'form-control', 'maxlength' => '50']) !!}

                @if ($errors->has('city'))
                <span class="form-text">
                    <strong>{{ $errors->first('city') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('state') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                State/ County: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('state', old('state'), ['id' => 'state', 'class' => 'form-control', 'maxlength' => '50']) !!}

                @if ($errors->has('state'))
                <span class="form-text">
                    <strong>{{ $errors->first('state') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('country_code') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Country: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('country_code', dropDown('countries', 'Please select'), old('country_code', $shipment->depot->country_code), array('id' => 'country_code', 'class' => 'form-control')) !!}

                @if ($errors->has('country_code'))
                <span class="form-text">
                    <strong>{{ $errors->first('country_code') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('message') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Message: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('message', old('message'), ['id' => 'message', 'class' => 'form-control']) !!}

                @if ($errors->has('message'))
                <span class="form-text">
                    <strong>{{ $errors->first('message') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('date') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Estimated Delivery Date:
            </label>

            <div class="col-sm-3">
                {!! Form::select('estimated_delivery_date', dropDown('datesFuture', 'Unknown'), old('estimated_delivery_date'), array('id' => 'estimated_delivery_date', 'class' => 'form-control')) !!}

                @if ($errors->has('estimated_delivery_date'))
                <span class="form-text">
                    <strong>{{ $errors->first('estimated_delivery_date') }}</strong>
                </span>
                @endif

            </div>

            <div class="col-sm-3">
                {!! Form::select('estimated_delivery_time', dropDown('times', 'Unknown'), old('estimated_delivery_time'), array('id' => 'estimated_delivery_time', 'class' => 'form-control')) !!}

                @if ($errors->has('estimated_delivery_time'))
                <span class="form-text">
                    <strong>{{ $errors->first('estimated_delivery_time') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row buttons-main">
            <div class="col-sm-4">&nbsp;</div>
            <div class="col-sm-6">
                <a class="back btn btn-secondary" role="button">Cancel</a>
                <button type="submit" class="btn btn-primary">{{$submitButtonText}}</button>
            </div>
        </div>

    </div>
</div>
