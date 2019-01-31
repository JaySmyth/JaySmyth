<div class="row">

    <div class="col-sm-6">

        <div class="form-group row{{ $errors->has('name') ? ' has-danger' : '' }}">

            <label class="col-sm-5  col-form-label">Name: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::Text('name', old('name'), ['id' => 'name','class' => 'form-control', 'maxlength' => '50', 'placeholder' => 'The Rug House']) !!}

                @if ($errors->has('name'))
                <span class="form-text">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('to') ? ' has-danger' : '' }}">

            <label class="col-sm-5  col-form-label">To: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::Text('to', old('to'), ['id' => 'to', 'class' => 'form-control', 'maxlength' => '255', 'placeholder' => 'email1@test.com; email2@test.com']) !!}

                @if ($errors->has('to'))
                <span class="form-text">
                    <strong>{{ $errors->first('to') }}</strong>
                </span>
                @endif
            </div>

        </div>

        <div class="form-group row{{ $errors->has('bcc') ? ' has-danger' : '' }}">

            <label class="col-sm-5  col-form-label">Bcc: </label>

            <div class="col-sm-6">
                {!! Form::Text('bcc', old('bcc'), ['id' => 'bcc', 'class' => 'form-control', 'maxlength' => '255', 'placeholder' => 'email1@test.com; email2@test.com']) !!}

                @if ($errors->has('bcc'))
                <span class="form-text">
                    <strong>{{ $errors->first('bcc') }}</strong>
                </span>
                @endif
            </div>

        </div>

        <div class="form-group row{{ $errors->has('format') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Format: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('format', dropDown('fileFormats'), old('format'), array('id' => 'format', 'class' => 'form-control')) !!}

                @if ($errors->has('format'))
                <span class="form-text">
                    <strong>{{ $errors->first('format') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('criteria') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Criteria: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                <textarea name="criteria"  rows="4" maxlength="255" class="form-control">@if($recipient->criteria){{$recipient->criteria}}@elseif(old('criteria')){{old('criteria')}}@else{"company_id":[999,999]}@endif</textarea>

                @if ($errors->has('criteria'))
                <span class="form-text">
                    <strong>{{ $errors->first('criteria') }}</strong>
                </span>
                @endif

            </div>
        </div>


        <div class="form-group row{{ $errors->has('frequency') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Frequency: <abbr title="This infrequencyion is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('frequency', dropDown('frequency'), old('frequency'), array('id' => 'frequency', 'class' => 'form-control')) !!}

                @if ($errors->has('frequency'))
                <span class="form-text">
                    <strong>{{ $errors->first('frequency') }}</strong>
                </span>
                @endif

            </div>
        </div>


        <div class="form-group row{{ $errors->has('time') ? ' has-danger' : '' }}">

            <label class="col-sm-5  col-form-label">
                Time: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('time', old('time'), ['id' => 'time', 'class' => 'form-control', 'maxlength' => '11', 'placeholder' => '17:15']) !!}

                @if ($errors->has('time'))
                <span class="form-text">
                    <strong>{{ $errors->first('time') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('enabled') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Enabled: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('enabled', dropDown('boolean'), old('enabled'), array('id' => 'enabled', 'class' => 'form-control')) !!}

                @if ($errors->has('enabled'))
                <span class="form-text">
                    <strong>{{ $errors->first('enabled') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row buttons-main">
            <div class="col-sm-5">&nbsp;</div>
            <div class="col-sm-6">
                <a class="back btn btn-secondary" role="button">Cancel</a>
                <button size="submit" class="btn btn-primary">{{$submitButtonText}}</button>
            </div>
        </div>
    </div>

</div>