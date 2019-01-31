<div class="row">
    <div class="col-sm-6">

        <div class="form-group row{{ $errors->has('name') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                Name: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('name', old('name'), ['id' => 'name', 'class' => 'form-control']) !!}

                @if ($errors->has('name'))
                <span class="form-text">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('email') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                Email: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('email', old('email'), ['id' => 'email', 'class' => 'form-control', 'maxlength' => '150']) !!}

                @if ($errors->has('email'))
                <span class="form-text">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif

            </div>
        </div>

        @if(!Request::is('users/*/edit'))
        <div class="form-group row{{ $errors->has('company_id') ? ' has-danger' : '' }}">

            <label class="col-sm-3  col-form-label">
                Company: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('company_id', dropDown('enabledSites', 'Please select'), old('company_id'), array('id' => 'company_id', 'class' => 'form-control')) !!}

                @if ($errors->has('company_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('company_id') }}</strong>
                </span>
                @endif

            </div>
        </div>
        @endif


        <div class="form-group row{{ $errors->has('telephone') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                Telephone: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('telephone', old('telephone'), ['id' => 'telephone', 'class' => 'form-control', 'maxlength' => '15']) !!}

                @if ($errors->has('telephone'))
                <span class="form-text">
                    <strong>{{ $errors->first('telephone') }}</strong>
                </span>
                @endif

            </div>
        </div>

        @if(Auth::user()->hasIfsRole())
        <div class="form-group row{{ $errors->has('role_id') ? ' has-danger' : '' }}">      
            <label class="col-sm-3  col-form-label">Primary Role: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">

                @if(isset($user))

                @if($user->hasIfsRole())
                {!! Form::select('role_id', dropDown('roles_ifs', 'Please select'), old('role_id', $primary_role), array('id' => 'role_id', 'class' => 'form-control')) !!}
                @else
                {!! Form::select('role_id', dropDown('roles_customer', 'Please select'), old('role_id', $primary_role), array('id' => 'role_id', 'class' => 'form-control')) !!}
                @endif

                @else
                {!! Form::select('role_id', [], old('role_id'), array('id' => 'role_id', 'class' => 'form-control')) !!}
                @endif

                @if ($errors->has('role_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('role_id') }}</strong>
                </span>
                @endif
            </div>

        </div>
        @endif

        <div class="form-group row">          
            <label class="col-sm-3  col-form-label">
                Localisation: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('localisation_id', dropDown('localisations'), old('localisation_id'), array('id' => 'localisation_id', 'class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group row">          
            <label class="col-sm-3  col-form-label">
                Label Size: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('print_format_id', dropDown('printFormats'), old('print_format_id'), array('id' => 'print_format_id', 'class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group row">          
            <label class="col-sm-3  col-form-label">
                Extra Label Copies: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('label_copies', dropDown('labelCopies'), old('label_copies'), array('id' => 'label_copies', 'class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group row{{ $errors->has('enabled') ? ' has-danger' : '' }}">          
            <label class="col-sm-3  col-form-label">
                Enabled: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('enabled',dropDown('boolean'), old('enabled'), array('id' => 'enabled', 'class' => 'form-control')) !!}

                @if ($errors->has('enabled'))
                <span class="form-text">
                    <strong>{{ $errors->first('enabled') }}</strong>
                </span>
                @endif

            </div>
        </div>

        @if(!Request::is('users/*/edit'))
        <div class="form-group row">   
            <br>
            <div class="col-sm-3  col-form-label">&nbsp;</div>
            <div class="col-sm-9 checkbox-secondary">
                {!! Form::checkbox('send_email', 1, old('send_email', 1)) !!} <span>Send email to user</span>                
            </div>
        </div>
        @endif

    </div>

    @if(Auth::user()->hasIfsRole())
    <div class="col-sm-6">
        <div id="panel-select-roles" class="card">
            <div class="card-header">Additional Roles</div>
            <div class="card-body">
                <ul class="roles">
                    @foreach ($roles as $role)
                    <li>{!! Form::checkbox('roles[]', $role->id) !!} {{$role->label}}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
    
    @include('partials.submit_buttons', ['submitButtonText' => $submitButtonText])
</div>

