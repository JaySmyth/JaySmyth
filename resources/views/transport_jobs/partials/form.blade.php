{!! Form::hidden('type', substr(strtolower($type), 0, 1), array('id' => 'type')) !!}
{!! Form::hidden('depot_id',1) !!}

<div class="row">

    <div class="col-sm-6">
        @include('transport_jobs.partials.address', ['type' => $type, 'address' => $address])
    </div>

    <div class="col-sm-6">

        <div class="form-group row{{ $errors->has('department_id') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Department: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('department_id', dropDown('departments', 'Please select'), old('department_id'), array('id' => 'department_id', 'class' => 'form-control')) !!}

                @if ($errors->has('department_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('department_id') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('scs_job_number') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                SCS Job Number:
            </label>

            <div class="col-sm-7">
                {!! Form::Text('scs_job_number', old('scs_job_number'), ['id' => 'scs_job_number', 'class' => 'form-control', 'maxlength' => '14']) !!}

                @if ($errors->has('scs_job_number'))
                <span class="form-text">
                    <strong>{{ $errors->first('scs_job_number') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('pieces') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Pieces: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('pieces', old('pieces'), ['id' => 'pieces', 'class' => 'form-control numeric-only', 'maxlength' => '3']) !!}

                @if ($errors->has('pieces'))
                <span class="form-text">
                    <strong>{{ $errors->first('pieces') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('weight') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Weight (kg): <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('weight', old('weight'), ['id' => 'weight', 'class' => 'form-control decimal-only', 'maxlength' => '6']) !!}

                @if ($errors->has('weight'))
                <span class="form-text">
                    <strong>{{ $errors->first('weight') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('dimensions') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Dimensions (cm): <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                <textarea name="dimensions" id="dimensions" rows="2" maxlength="255" class="form-control">@if(old('dimensions')){{ old('dimensions')}}@elseif(isset($transportJob)){{$transportJob->dimensions}}@endif</textarea>

                @if ($errors->has('dimensions'))
                <span class="form-text">
                    <strong>{{ $errors->first('dimensions') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('goods_description') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Goods Description: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('goods_description', old('goods_description'), ['id' => 'goods_description', 'class' => 'form-control', 'maxlength' => '100']) !!}

                @if ($errors->has('goods_description'))
                <span class="form-text">
                    <strong>{{ $errors->first('goods_description') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('final_destination') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Final Destination: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('final_destination', old('final_destination'), ['id' => 'final_destination', 'class' => 'form-control', 'maxlength' => '35']) !!}

                @if ($errors->has('final_destination'))
                <span class="form-text">
                    <strong>{{ $errors->first('final_destination') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('instructions') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Instructions / Additional Info: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                <textarea name="instructions" id="instructions" rows="2" maxlength="255" class="form-control">@if(old('instructions')){{ old('instructions')}}@elseif(isset($transportJob)){{$transportJob->instructions}}@endif</textarea>

                @if ($errors->has('instructions'))
                <span class="form-text">
                    <strong>{{ $errors->first('instructions') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('closing_time') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Closing Time: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('closing_time', dropDown('times'), old('closing_time', '17:00'), array('id' => 'closing_time', 'class' => 'form-control')) !!}

                @if ($errors->has('closing_time'))
                <span class="form-text">
                    <strong>{{ $errors->first('closing_time') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('cash_on_delivery') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Cash On <span class="text-capitalize">Collection</span> (£): <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('cash_on_delivery', old('cash_on_delivery'), ['id' => 'cash_on_delivery', 'class' => 'form-control decimal-only', 'maxlength' => '6']) !!}

                @if ($errors->has('cash_on_delivery'))
                <span class="form-text">
                    <strong>{{ $errors->first('cash_on_delivery') }}</strong>
                </span>
                @endif

            </div>
        </div>
        
    </div>

    @include('partials.submit_buttons', ['submitButtonText' => $submitButtonText])

</div>