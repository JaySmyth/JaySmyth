<div class="row">

    <div class="col-sm-6">

        <div class="form-group row{{ $errors->has('size') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Container Size: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">            
                {!! Form::select('size', dropDown('containerSizes'), old('size'), array('id' => 'size', 'class' => 'form-control')) !!}

                @if ($errors->has('size'))
                <span class="form-text">
                    <strong>{{ $errors->first('size') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('number') ? ' has-danger' : '' }}">  

            <label class="col-sm-5  col-form-label">Container Number: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::Text('number', old('number'), ['id' => 'number', 'class' => 'form-control', 'maxlength' => '50']) !!}

                @if ($errors->has('number'))
                <span class="form-text">
                    <strong>{{ $errors->first('number') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('goods_description') ? ' has-danger' : '' }}">  

            <label class="col-sm-5  col-form-label">Description of Goods: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::Text('goods_description', old('goods_description'), ['id' => 'goods_description', 'class' => 'form-control', 'maxlength' => '100']) !!}

                @if ($errors->has('goods_description'))
                <span class="form-text">
                    <strong>{{ $errors->first('goods_description') }}</strong>
                </span>
                @endif
            </div>

        </div>

        <div class="form-group row{{ $errors->has('number_of_cartons') ? ' has-danger' : '' }}">  

            <label class="col-sm-5  col-form-label">Number of Cartons: <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::Text('number_of_cartons', old('number_of_cartons'), ['id' => 'number_of_cartons', 'class' => 'form-control decimal-only', 'maxlength' => '10']) !!}

                @if ($errors->has('number_of_cartons'))
                <span class="form-text">
                    <strong>{{ $errors->first('number_of_cartons') }}</strong>
                </span>
                @endif
            </div>

        </div>      

        <div class="form-group row{{ $errors->has('weight') ? ' has-danger' : '' }}">  

            <label class="col-sm-5  col-form-label">Weight (KG): <abbr title="This information is required.">*</abbr></label>

            <div class="col-sm-6">
                {!! Form::Text('weight', old('weight'), ['id' => 'weight', 'class' => 'form-control decimal-only', 'maxlength' => '15']) !!}

                @if ($errors->has('weight'))
                <span class="form-text">
                    <strong>{{ $errors->first('weight') }}</strong>
                </span>
                @endif
            </div>

        </div>

        <div class="form-group row{{ $errors->has('seal_number') ? ' has-danger' : '' }}">  

            <label class="col-sm-5  col-form-label">Seal Number:</label>

            <div class="col-sm-6">
                {!! Form::Text('seal_number', old('seal_number'), ['id' => 'seal_number', 'class' => 'form-control', 'maxlength' => '50']) !!}

                @if ($errors->has('seal_number'))
                <span class="form-text">
                    <strong>{{ $errors->first('seal_number') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row buttons-main">
            <div class="col-sm-5">&nbsp;</div>
            <div class="col-sm-6">
                <a href="{{ url('/sea-freight')}}" class="btn btn-secondary" role="button">Cancel</a>
                <button size="submit" class="btn btn-primary">{{$submitButtonText}}</button>
            </div>
        </div>
    </div>

</div>