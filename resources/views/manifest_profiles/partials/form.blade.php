<div class="row">

    <div class="col-sm-5">

        <div class="form-group row{{ $errors->has('name') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                Name: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-8">
                {!! Form::Text('name', old('name'), ['id' => 'name', 'class' => 'form-control', 'maxlength' => '50']) !!}

                @if ($errors->has('name'))
                <span class="form-text">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('prefix') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                Prefix: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-8">
                {!! Form::Text('prefix', old('prefix'), ['id' => 'prefix', 'class' => 'form-control', 'maxlength' => '4']) !!}

                @if ($errors->has('prefix'))
                <span class="form-text">
                    <strong>{{ $errors->first('prefix') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('depot_id') ? ' has-danger' : '' }}">          
            <label class="col-sm-3  col-form-label">
                Depot: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-8">
                {!! Form::select('depot_id', dropDown('depots'), old('depot_id'), array('id' => 'depot_id', 'class' => 'form-control')) !!}

                @if ($errors->has('depot_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('depot_id') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('mode_id') ? ' has-danger' : '' }}">

            <label class="col-sm-3  col-form-label">
                Mode: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-8">
                {!! Form::select('mode_id', dropDown('modes', 'Please select'), old('mode_id'), array('id' => 'carrier_id', 'class' => 'form-control')) !!}

                @if ($errors->has('mode_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('mode_id') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('carrier_id') ? ' has-danger' : '' }}">

            <label class="col-sm-3  col-form-label">
                Carrier: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-8">
                {!! Form::select('carrier_id', dropDown('carriers', 'Please select'), old('carrier_id'), array('id' => 'carrier_id', 'class' => 'form-control')) !!}

                @if ($errors->has('carrier_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('carrier_id') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('route_id') ? ' has-danger' : '' }}">

            <label class="col-sm-3  col-form-label">
                Route: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-8">
                {!! Form::select('route_id', dropDown('routes', 'Please select'), old('route_id'), array('id' => 'route_id', 'class' => 'form-control')) !!}

                @if ($errors->has('route_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('route_id') }}</strong>
                </span>
                @endif

            </div>
        </div>


        <div class="form-group row{{ $errors->has('auto') ? ' has-danger' : '' }}">

            <label class="col-sm-3  col-form-label">
                Auto Run: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-8">
                {!! Form::select('auto', dropDown('boolean', 'Please select'), old('auto'), array('id' => 'auto', 'class' => 'form-control')) !!}

                @if ($errors->has('auto'))
                <span class="form-text">
                    <strong>{{ $errors->first('auto') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('time') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                Auto Run Time: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-8">
                {!! Form::Text('time', old('time'), ['id' => 'prefix', 'class' => 'form-control', 'maxlength' => '5']) !!}

                @if ($errors->has('time'))
                <span class="form-text">
                    <strong>{{ $errors->first('time') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row buttons-main">
            <div class="col-sm-3">&nbsp;</div>
            <div class="col-sm-8">
                <a class="back btn btn-secondary" role="button">Cancel</a>
                <button type="submit" class="btn btn-primary">{{ $submitButtonText }}</button>   
            </div>
        </div>

    </div>

    <div class="col-sm-4">

        <div class="card">
            <div class="card-header">Services</div>
            <div class="card-body">
                @foreach ($services->sortBy('carrier_name') as $service)
                <div class="checkbox text-large">
                    <label>
                        {!! Form::checkbox('services[]', $service->id) !!}
                        {{$service->carrier_name}} - {{$service->carrier_code}} - {{$service->name}}
                    </label>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <div class="col-sm-3">

        <div class="card">
            <div class="card-header">Countries</div>
            <div class="card-body">
                @foreach ($countries as $country)
                <div class="checkbox text-large">
                    <label>
                        {!! Form::checkbox('countries[]', $country->id) !!}
                        {{$country->country}}
                    </label>
                </div>
                @endforeach
            </div>
        </div>

    </div>

</div>