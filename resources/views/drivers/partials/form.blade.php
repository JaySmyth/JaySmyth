<div class="col-sm-6">

    <div class="form-group row{{ $errors->has('name') ? ' has-danger' : '' }}">
        <label class="col-sm-3  col-form-label">
            Name: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::Text('name', old('name'), ['id' => 'name', 'class' => 'form-control', 'maxlength' => '30']) !!}

            @if ($errors->has('name'))
            <span class="form-text">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('telephone') ? ' has-danger' : '' }}">
        <label class="col-sm-3  col-form-label">
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

    <div class="form-group row{{ $errors->has('vehicle_id') ? ' has-danger' : '' }}">
        <label class="col-sm-3  col-form-label">
            Default Vehicle:
        </label>

        <div class="col-sm-6">
            {!! Form::select('vehicle_id', dropDown('vehicles', 'Not Defined'), old('vehicle_id'), array('id' => 'vehicle_id', 'class' => 'form-control')) !!}

            @if ($errors->has('vehicle_id'))
            <span class="form-text">
                <strong>{{ $errors->first('vehicle_id') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('depot_id') ? ' has-danger' : '' }}">
        <label class="col-sm-3  col-form-label">
            Depot: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::select('depot_id', dropDown('associatedDepots'), old('depot_id'), array('id' => 'depot_id', 'class' => 'form-control')) !!}

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

