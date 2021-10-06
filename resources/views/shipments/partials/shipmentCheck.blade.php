{!! Form::Open(['method' => 'POST', 'url' => 'shipments/'.$shipment->id.'/check', 'class' => 'form-compact', 'autocomplete' => 'off']) !!}

<div class="form-group row">

                <label class="col-md-5 col-form-label">
                    Services: <abbr title="This information is required.">*</abbr>
                </label>

                <div class="col-md-7">
                    {!! Form::select('service_id', $serviceDropdown, old('service_id'), array('id' => 'service_id', 'class' =>'form-control form-control-sm')) !!}
                    
                    <div class="float-right mb-2 buttons-main button">
                        <a class="back btn btn-secondary btn-shadow text-white" role="button">Cancel</a>
                        <button type="submit" class="ml-md-4 btn btn-primary btn-shadow">Submit</button>
                    </div>
                    
                </div>

    <table class="table table-striped">
    <thead>
    <tr>
        <th>Message</th>
    </tr>
    </thead>
    <tbody>

    @foreach($messages as $message)

        @if(stripos($message,'Failed') !== false)
            <tr class="table-warning">
        @else
            <tr>
        @endif

            <td>{{ $message }}</td>

        </tr>

    @endforeach

    </tbody>
</table>


</div>

{{ Form::close()  }}