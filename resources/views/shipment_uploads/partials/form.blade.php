<div class="row">
    <div class="col-sm-6">
        <div class="form-group row{{ $errors->has('directory') ? ' has-danger' : '' }}">
            <label class="col-sm-3 col-form-label">
                Directory: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('directory', old('directory'), ['id' => 'directory', 'class' => 'form-control', 'maxlength' => '200']) !!}

                @if ($errors->has('directory'))
                <span class="form-text">
                    <strong>{{ $errors->first('directory') }}</strong>
                </span>
                @endif

                <small id="passwordHelpBlock" class="form-text text-muted">
                    Directory should be in format /home/<b>username</b>/uploads/
                </small>
            </div>

        </div>

        <div class="form-group row{{ $errors->has('import_config_id') ? ' has-danger' : '' }}">
            <label class="col-sm-3 col-form-label">
                Import Config: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('import_config_id', dropDown('importConfigs', 'Please select'), old('import_config_id'), array('id' => 'import_config_id', 'class' => 'form-control')) !!}

                @if ($errors->has('import_config_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('import_config_id') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3  col-form-label">
                Enabled: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('enabled', dropDown('enabled'), old('enabled'), array('id' => 'enabled', 'class' => 'form-control')) !!}
            </div>
        </div>


        @if(Request::is('eeee'))
        <div class="form-group row mt-4">
            <label class="col-sm-3  col-form-label">
                SFTP account: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7 pt-1">
                <div class="custom-control custom-checkbox">
                    <input name="create_sftp_account" type="checkbox" class="custom-control-input" id="customCheck1">
                    <label class="custom-control-label" for="customCheck1">Yes, create an SFTP user account</label>
                </div>
            </div>
        </div>
        @endif

        @include('partials.submit_buttons', ['submitButtonText' => $submitButtonText])
    </div>
</div>





