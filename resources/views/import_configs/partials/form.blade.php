<div class="row">

    <input type="hidden" name="mode_id" value="1"/>

    <div class="col-sm-6">

        <div class="form-group row{{ $errors->has('company_id') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Company: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('company_id', dropDown('enabledSites', 'Please select'), old('company_id'), array('id' => 'company_id', 'class' => 'form-control')) !!}

                @if ($errors->has('company_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('company_id')}}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('company_name') ? ' has-danger' : '' }}">
            <label class="col-sm-4 col-form-label">
                Config Name: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('company_name', old('company_name'), ['id' => 'company_name', 'class' => 'form-control', 'maxlength' => '50']) !!}

                @if ($errors->has('company_name'))
                <span class="form-text">
                    <strong>{{ $errors->first('company_name') }}</strong>
                </span>
                @endif

                <small class="form-text text-muted">
                    Descriptive name for this import config e.g. The Rug House (ebay)
                </small>
            </div>

        </div>

        <div class="form-group row{{ $errors->has('start_row') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                1st Row Containing Data* :
            </label>

            <div class="col-sm-7">
                {!! Form::select("start_row", ["1" =>"1","2" =>"2","3" =>"3","4" =>"4","5" =>"5",], old("start_row"), array("id" => "start_row", "class" => "form-control")) !!}

                @if ($errors->has('start_row'))
                <span class="form-text">
                    <strong>{{ $errors->first('start_row') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has("delim") ? ' has-danger' : '' }}">          
            <label class="col-sm-4  col-form-label">
                Delimiter* :
            </label>

            <div class="col-sm-7">
                {!! Form::select("delim", dropDown("delimiters"), old("delim"), array("id" => "delim", "class" => "form-control")) !!}

                @if ($errors->has("delim"))
                <span class="form-text">
                    <strong>{{ $errors->first("delim") }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has("enabled") ? ' has-danger' : '' }}">          
            <label class="col-sm-4  col-form-label">
                Enabled* :
            </label>

            <div class="col-sm-7">
                {!! Form::select("enabled", dropDown("boolean"), old("enabled"), array("id" => "enabled", "class" => "form-control")) !!}

                @if ($errors->has("enabled"))
                <span class="form-text">
                    <strong>{{ $errors->first("enabled") }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has("test_mode" ) ? ' has-danger' : '' }}">          
            <label class="col-sm-4  col-form-label">
                Test Mode* :
            </label>

            <div class="col-sm-7">
                {!! Form::select("test_mode", dropDown("boolean"), old("test_mode"), array("id" => "test_mode", "class" => "form-control")) !!}

                @if ($errors->has("test_mode"))
                <span class="form-text">
                    <strong>{{ $errors->first("test_mode") }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has("default_service" ) ? ' has-danger' : '' }}">          
            <label class="col-sm-4  col-form-label">
                Default Service* :
            </label>

            <div class="col-sm-7">
                {!! Form::select("default_service", dropDown("serviceCodes", "Please Select"), old("default_service"), array("id" => "default_service", "class" => "form-control")) !!}

                @if ($errors->has("default_service"))
                <span class="form-text">
                    <strong>{{ $errors->first("default_service") }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has("default_terms" ) ? ' has-danger' : '' }}">          
            <label class="col-sm-4  col-form-label">
                Default Terms* :
            </label>

            <div class="col-sm-7">
                {!! Form::select("default_terms", dropDown("terms", "Please Select", 1), old("default_terms"), array("id" => "default_terms", "class" => "form-control")) !!}

                @if ($errors->has("default_terms"))
                <span class="form-text">
                    <strong>{{ $errors->first("default_terms") }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('default_goods_description') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Default Goods Description* :
            </label>

            <div class="col-sm-7">
                {!! Form::Text('default_goods_description', old('default_goods_description'), ['id' => 'default_goods_description', 'class' => 'form-control', 'maxlength' => 30]) !!}

                @if ($errors->has('default_goods_description'))
                <span class="form-text">
                    <strong>{{ $errors->first('default_goods_description') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('default_pieces') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Default Pieces :
            </label>

            <div class="col-sm-7">
                {!! Form::Text('default_pieces', old('default_pieces'), ['id' => 'default_pieces', 'class' => 'form-control', 'maxlength' => 4]) !!}

                @if ($errors->has('default_pieces'))
                <span class="form-text">
                    <strong>{{ $errors->first('default_pieces') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('default_weight') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Default Weight :
            </label>

            <div class="col-sm-7">
                {!! Form::Text('default_weight', old('default_weight'), ['id' => 'default_weight', 'class' => 'form-control', 'maxlength' => 8]) !!}

                @if ($errors->has('default_weight'))
                <span class="form-text">
                    <strong>{{ $errors->first('default_weight') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('default_customs_value') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Default Customs Value :
            </label>

            <div class="col-sm-7">
                {!! Form::Text('default_customs_value', old('default_customs_value'), ['id' => 'default_customs_value', 'class' => 'form-control', 'maxlength' => 10]) !!}

                @if ($errors->has('default_customs_value'))
                <span class="form-text">
                    <strong>{{ $errors->first('default_customs_value') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('default_recipient_name') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Default Recipient Name :
            </label>

            <div class="col-sm-7">
                {!! Form::Text('default_recipient_name', old('default_recipient_name'), ['id' => 'default_recipient_name', 'class' => 'form-control', 'maxlength' => 30]) !!}

                @if ($errors->has('default_recipient_name'))
                <span class="form-text">
                    <strong>{{ $errors->first('default_recipient_name') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('default_recipient_email') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Default Recipient Email :
            </label>

            <div class="col-sm-7">
                {!! Form::Text('default_recipient_email', old('default_recipient_email'), ['id' => 'default_recipient_email', 'class' => 'form-control', 'maxlength' => 30]) !!}

                @if ($errors->has('default_recipient_email'))
                <span class="form-text">
                    <strong>{{ $errors->first('default_recipient_email') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('ship_ref_sep') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Ship Reference Separator :
            </label>

            <div class="col-sm-7">
                {!! Form::Text('ship_ref_sep', old('ship_ref_sep'), ['id' => 'ship_ref_sep', 'class' => 'form-control', 'maxlength' => 3]) !!}

                @if ($errors->has('ship_ref_sep'))
                <span class="form-text">
                    <strong>{{ $errors->first('ship_ref_sep') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('cc_import_results_email') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                CC Import Results Email :
            </label>

            <div class="col-sm-7">
                {!! Form::Text('cc_import_results_email', old('cc_import_results_email'), ['id' => 'cc_import_results_email', 'class' => 'form-control']) !!}

                @if ($errors->has('cc_import_results_email'))
                <span class="form-text">
                    <strong>{{ $errors->first('cc_import_results_email') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-4"></div>
            <div class="col-sm-7">
                @include('partials.submit_buttons', ['submitButtonText' => $submitButtonText])
            </div>
        </div>


    </div>

    <div class="col-sm-6">

        @foreach (getExcelColumNames() as $column)

        <div class="form-group row{{ $errors->has("column" . $loop->index ) ? ' has-danger' : '' }}">          
            <label class="col-sm-4 col-form-label">
                Col {{$column}}:
            </label>

            <div class="col-sm-7">
                {!! Form::select("column" . $loop->index, $fields, old("column" . $loop->index), array("id" => "column" . $loop->index, "class" => "form-control")) !!}

                @if ($errors->has("column" . $loop->index))
                <span class="form-text">
                    <strong>{{ $errors->first("column" . $loop->index) }}</strong>
                </span>
                @endif

            </div>
        </div>

        @endforeach
    </div>

</div>