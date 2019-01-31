<div class="form-group row{{ $errors->has($address . '_name') ? ' has-danger' : '' }}">          
    <label class="col-sm-4  col-form-label">
        Name: <abbr title="This information is required.">*</abbr>
    </label>

    <div class="col-sm-7">
        {!! Form::Text($address . '_name', old($address . '_name'), ['id' => $address . '_name', 'class' => 'form-control', 'maxlength' => 35]) !!}      

        @if ($errors->has($address . '_name'))
        <span class="form-text">
            <strong>{{ $errors->first($address . '_name') }}</strong>
        </span>
        @endif
    </div>
</div>

<div class="form-group row{{ $errors->has($address . '_company_name') ? ' has-danger' : '' }}">
    <label class="col-sm-4  col-form-label">
        Company: <abbr title="This information is required.">*</abbr>
    </label>

    <div class="col-sm-7">
        {!! Form::Text($address . '_company_name', old($address . '_company_name'), ['id' => $address . '_company_name', 'class' => 'form-control', 'maxlength' => 35]) !!}

        @if ($errors->has($address . '_company_name'))
        <span class="form-text">
            <strong>{{ $errors->first($address . '_company_name') }}</strong>
        </span>
        @endif
    </div>
</div>

<div class="form-group row{{ $errors->has($address . '_address1') ? ' has-danger' : '' }}">
    <label class="col-sm-4  col-form-label">
        Address 1: <abbr title="This information is required.">*</abbr>
    </label>

    <div class="col-sm-7">
        {!! Form::Text($address . '_address1', old($address . '_address1'), ['id' => $address . '_address1', 'class' => 'form-control', 'maxlength' => 35]) !!}

        @if ($errors->has($address . '_address1'))
        <span class="form-text">
            <strong>{{ $errors->first($address . '_address1') }}</strong>
        </span>
        @endif
    </div>
</div>

<div class="form-group row{{ $errors->has($address . '_address2') ? ' has-danger' : '' }}"> 
    <label class="col-sm-4  col-form-label">
        Address 2: <abbr title="This information is required.">*</abbr>
    </label>

    <div class="col-sm-7">
        {!! Form::Text($address . '_address2', old($address . '_address2'), ['id' => $address . '_address2', 'class' => 'form-control', 'maxlength' => 35]) !!}

        @if ($errors->has($address . '_address2'))
        <span class="form-text">
            <strong>{{ $errors->first($address . '_address2') }}</strong>
        </span>
        @endif
    </div>
</div>

<div class="form-group row{{ $errors->has($address . '_city') ? ' has-danger' : '' }}">
    <label class="col-sm-4  col-form-label">
        City: <abbr title="This information is required.">*</abbr>
    </label>

    <div class="col-sm-7">
        {!! Form::Text($address . '_city', old($address . '_city'), ['id' => $address . '_city', 'class' => 'form-control', 'maxlength' => 35]) !!}

        @if ($errors->has($address . '_city'))
        <span class="form-text">
            <strong>{{ $errors->first($address . '_city') }}</strong>
        </span>
        @endif
    </div>
</div>

<div class="form-group row{{ $errors->has($address . '_state') ? ' has-danger' : '' }}">
    <label class="col-sm-4  col-form-label">
        County: <abbr title="This information is required.">*</abbr>
    </label>

    <div class="col-sm-7" id="{{$address }}-state-placeholder">
        {!! Form::Text($address . '_state', old($address . '_state'), ['id' => $address . '_state', 'class' => 'form-control', 'maxlength' => 35]) !!}

        @if ($errors->has($address . '_state'))
        <span class="form-text">
            <strong>{{ $errors->first($address . '_state') }}</strong>
        </span>
        @endif
    </div>
</div>

<div class="form-group row{{ $errors->has($address . '_postcode') ? ' has-danger' : '' }}">
    <label class="col-sm-4  col-form-label">
        Postcode: <abbr title="This information is required.">*</abbr>
    </label>

    <div class="col-sm-7">
        {!! Form::Text($address . '_postcode', old($address . '_postcode'), ['id' => $address . '_postcode', 'class' => 'form-control', 'maxlength' => 10]) !!}

        @if ($errors->has($address . '_postcode'))
        <span class="form-text">
            <strong>{{ $errors->first($address . '_postcode') }}</strong>
        </span>
        @endif
    </div>
</div>

<div class="form-group row{{ $errors->has($address . '_telephone') ? ' has-danger' : '' }}">
    <label class="col-sm-4  col-form-label">
        Telephone: <abbr title="This information is required.">*</abbr>
    </label>

    <div class="col-sm-7">
        {!! Form::Text($address . '_telephone', old($address . '_telephone'), ['id' => $address . '_telephone', 'class' => 'form-control', 'maxlength' => 35]) !!}

        @if ($errors->has($address . '_telephone'))
        <span class="form-text">
            <strong>{{ $errors->first($address . '_telephone') }}</strong>
        </span>
        @endif
    </div>
</div>

<br><br><br>

<div class="form-group row{{ $errors->has('reference') ? ' has-danger' : '' }}">
    <label class="col-sm-4  col-form-label">
        Customer Reference: <abbr title="This information is required.">*</abbr>
    </label>

    <div class="col-sm-7">
        {!! Form::Text('reference', old('reference'), ['id' => 'reference', 'class' => 'form-control', 'maxlength' => '40']) !!}

        @if ($errors->has('reference'))
        <span class="form-text">
            <strong>{{ $errors->first('reference') }}</strong>
        </span>
        @endif

    </div>
</div>

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Date / Time Available: <abbr title="This information is required.">*</abbr>
    </label>

    <div class="col-sm-4">
        {!! Form::select('date', dropDown('datesShort'), old('date', date('d-m-Y', strtotime(!isset($transportJob->date_requested) ? 'today' : $transportJob->date_requested))), array('id' => 'date', 'class' => 'form-control')) !!}

        @if ($errors->has('date'))
        <span class="form-text">
            <strong>{{ $errors->first('date') }}</strong>
        </span>
        @endif

    </div>

    <div class="col-sm-3">
        {!! Form::select('time', dropDown('times'), old('time', date('H:i', strtotime(!isset($transportJob->date_requested) ? '12:00' : $transportJob->date_requested))), array('id' => 'time', 'class' => 'form-control')) !!}

        @if ($errors->has('time'))
        <span class="form-text">
            <strong>{{ $errors->first('time') }}</strong>
        </span>
        @endif

    </div>
</div>