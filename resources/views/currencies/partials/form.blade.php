<div class="col-sm-6">

    <div class="form-group row{{ $errors->has('currency') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Currency: <abbr title="This information is required.">*</abbr>            
        </label>

        <div class="col-sm-6">
            {!! Form::Text('currency', old('currency'), ['id' => 'currency', 'class' => 'form-control', 'maxlength' => '50']) !!}

            @if ($errors->has('currency'))
            <span class="form-text">
                <strong>{{ $errors->first('currency') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('code') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Code: <abbr title="This information is required.">*</abbr>            
        </label>

        <div class="col-sm-6">
            {!! Form::Text('code', old('code'), ['id' => 'code', 'class' => 'form-control', 'maxlength' => '3']) !!}

            @if ($errors->has('code'))
            <span class="form-text">
                <strong>{{ $errors->first('code') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('display_order') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Display Order: <abbr title="This information is required.">*</abbr>            
        </label>

        <div class="col-sm-6">
            {!! Form::Text('display_order', old('display_order'), ['id' => 'display_order', 'class' => 'form-control numeric-only', 'maxlength' => '2']) !!}

            @if ($errors->has('display_order'))
            <span class="form-text">
                <strong>{{ $errors->first('display_order') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('rate') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Exchange Rate: <abbr title="This information is required.">*</abbr>            
        </label>

        <div class="col-sm-6">
            {!! Form::Text('rate', old('rate'), ['id' => 'rate', 'class' => 'form-control', 'maxlength' => '10']) !!}

            @if ($errors->has('rate'))
            <span class="form-text">
                <strong>{{ $errors->first('rate') }}</strong>
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

