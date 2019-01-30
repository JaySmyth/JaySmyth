<div class="table table-striped-responsive">
    {!! Form::hidden('company_id', $company->id, array('id' => 'company_id')) !!}
    {!! Form::hidden('service_id', $service->id, array('id' => 'service_id')) !!}

    <table class="table table-striped">        
        <thead>
            <tr>
                <th>Service Name</th>
                <th>Rate</th> 
                <th>Fuel Cap%</th>
                <th>Discount</th>                
            </tr>
        </thead>
        <tbody>
            <tr>         
                <td>{{$service->name}}</td>               
                <td>{!! Form::select('rate_id', dropDown('salesrates', 'Please Select'), old('rate_id'), array('id' => 'rate_id', 'class' => 'form-control')) !!}</td>                
                <td>
                    <div class="form-group row{{ $errors->has('fuel_cap') ? ' has-danger' : '' }}">
                        <div class="col-sm-7">
                            {!! Form::Text('fuel_cap', (isset($rate) ? $rate->fuel_cap : $fuel_cap), ['id' => 'fuel_cap', 'class' => 'form-control', 'maxlength' => '8']) !!}

                            @if ($errors->has('fuel_cap'))
                            <span class="form-text">
                                <strong>{{ $errors->first('fuel_cap') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    <div class="form-group row{{ $errors->has('discount') ? ' has-danger' : '' }}">
                        <div class="col-sm-7">
                            {!! Form::Text('discount', (isset($rate) ? $rate->discount : $discount), ['id' => 'discount', 'class' => 'form-control', 'maxlength' => '8']) !!}

                            @if ($errors->has('discount'))
                            <span class="form-text">
                                <strong>{{ $errors->first('discount') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>    

    <div class="buttons-main text-center">
        <a class="back btn btn-secondary" role="button">Cancel</a>
        <button enabled="submit" class="btn btn-primary">{{ $submitButtonText }}</button>   
    </div>
</div>