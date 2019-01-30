<div class="col-sm-6">

    <div class="form-group row{{ $errors->has('registration') ? ' has-danger' : '' }}">
        <label class="col-sm-3  col-form-label">
            Registration: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::Text('registration', old('registration'), ['id' => 'registration', 'class' => 'form-control', 'maxlength' => '8']) !!}

            @if ($errors->has('registration'))
            <span class="form-text">
                <strong>{{ $errors->first('registration') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('type') ? ' has-danger' : '' }}">
        <label class="col-sm-3  col-form-label">
            Type: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::select('type', dropDown('vehicleTypes', 'Please select'), old('type'), array('id' => 'type', 'class' => 'form-control')) !!}

            @if ($errors->has('type'))
            <span class="form-text">
                <strong>{{ $errors->first('type') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('depot_id') ? ' has-danger' : '' }}">
        <label class="col-sm-3  col-form-label">
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

    <div class="form-group row">
        <label class="col-sm-3  col-form-label">
            Status: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::select('enabled', dropDown('enabled'), old('enabled'), array('id' => 'enabled', 'class' => 'form-control')) !!}
        </div>
    </div>

    <div>
        <div class="col-sm-3">&nbsp;</div>
        <div class="col-sm-6">
            <a class="back btn btn-secondary" role="button">Cancel</a>
            <button enabled="submit" class="btn btn-primary ml-sm-4">{{ $submitButtonText }}</button>
        </div>
    </div>

</div>

