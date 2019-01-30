<div class="col-sm-6">

    <div class="form-group row{{ $errors->has('postcode') ? ' has-danger' : '' }}">
        <label class="col-sm-5 col-form-label">
            Postcode: <abbr title="This information is required.">*</abbr>            
        </label>

        <div class="col-sm-6">
            {!! Form::Text('postcode', old('postcode'), ['id' => 'postcode', 'class' => 'form-control', 'maxlength' => '12']) !!}

            @if ($errors->has('postcode'))
            <span class="form-text">
                <strong>{{ $errors->first('postcode') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('pickup_time') ? ' has-danger' : '' }}">
        <label class="col-sm-5 col-form-label">
            Collection/Delivery Time: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::Text('pickup_time', old('pickup_time'), ['id' => 'pickup_time', 'class' => 'form-control', 'maxlength' => '5']) !!}

            @if ($errors->has('time'))
            <span class="form-text">
                <strong>{{ $errors->first('pickup_time') }}</strong>
            </span>
            @endif
        </div>
    </div>


    <div class="form-group row{{ $errors->has('collection_route') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Collection Route: <abbr title="This information is required.">*</abbr>            
        </label>

        <div class="col-sm-6">
            {!! Form::Text('collection_route', old('collection_route'), ['id' => 'collection_route', 'class' => 'form-control', 'maxlength' => '10']) !!}

            @if ($errors->has('collection_route'))
            <span class="form-text">
                <strong>{{ $errors->first('collection_route') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('delivery_route') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Delivery Route: <abbr title="This information is required.">*</abbr>            
        </label>

        <div class="col-sm-6">
            {!! Form::Text('delivery_route', old('delivery_route'), ['id' => 'delivery_route', 'class' => 'form-control', 'maxlength' => '10']) !!}

            @if ($errors->has('delivery_route'))
            <span class="form-text">
                <strong>{{ $errors->first('delivery_route') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('country_code') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Country: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::select('country_code', dropDown('countries'), old('country_code', 'GB'), array('id' => 'country_code', 'class' => 'form-control')) !!}

            @if ($errors->has('country_code'))
            <span class="form-text">
                <strong>{{ $errors->first('country_code') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="row mt-4">
        <div class="col-sm-5">&nbsp;</div>
        <div class="col-sm-6">
            <a class="back btn btn-secondary" role="button">Cancel</a>
            <button enabled="submit" class="btn btn-primary ml-sm-4">{{ $submitButtonText }}</button>
        </div>
    </div>

</div>

