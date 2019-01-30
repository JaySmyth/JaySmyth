<div class="row">

    <div class="col-sm-6">

        <div class="form-group row{{ $errors->has('title') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                Title: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('title', old('title'), ['id' => 'title', 'class' => 'form-control', 'maxlength' => '100']) !!}

                @if ($errors->has('title'))
                <span class="form-text">
                    <strong>{{ $errors->first('title') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('message') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                Message: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                <textarea name="message" id="message" rows="12" maxlength="1000" class="form-control">@if(old('message')){{ old('message')}}@elseif(isset($message)){{$message->message}}@endif</textarea>

                @if ($errors->has('message'))
                <span class="form-text">
                    <strong>{{ $errors->first('message') }}</strong>
                </span>
                @endif

            </div>
        </div>


        <div class="form-group row{{ $errors->has('valid_to') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                Date Range: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-3">
                {!! Form::Text('valid_from', old('valid_from'), ['id' => 'valid_from', 'class' => 'form-control bg-white datepicker', 'maxlength' => '10', 'readonly' => 'readonly']) !!}

                @if ($errors->has('valid_from'))
                <span class="form-text">
                    <strong>{{ $errors->first('valid_from') }}</strong>
                </span>
                @endif

            </div>

            <div class="col-sm-3">
                {!! Form::Text('valid_to', old('valid_to'), ['id' => 'valid_to', 'class' => 'form-control bg-white datepicker', 'maxlength' => '10', 'readonly' => 'readonly']) !!}

                @if ($errors->has('valid_to'))
                <span class="form-text">
                    <strong>{{ $errors->first('valid_to') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3  col-form-label">
                Sticky: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('sticky', dropDown('boolean'), old('sticky'), array('id' => 'sticky', 'class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3  col-form-label">
                IFS Only: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('ifs_only', dropDown('boolean'), old('ifs_only'), array('id' => 'ifs_only', 'class' => 'form-control')) !!}
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


    </div>

    <div class="col-sm-6">

        <div class="form-group row">
            <label class="col-sm-3  col-form-label">
                Depots: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::select('depots[]', dropDown('depots'), old('depots'), array('id' => 'depots', 'class' => 'form-control', 'multiple', 'size' => 6)) !!}
            </div>
        </div>




        <div class="form-group row">
            <label class="col-sm-3  col-form-label">
                Company Exclusions: 
            </label>

            <div class="col-sm-6">
                {!! Form::select('companies[]', dropDown('enabledSites'), old('companies'), array('id' => 'companies', 'class' => 'form-control', 'multiple', 'size' => 19)) !!}
            </div>
        </div>

    </div>

    @include('partials.submit_buttons', ['submitButtonText' => $submitButtonText])
</div>