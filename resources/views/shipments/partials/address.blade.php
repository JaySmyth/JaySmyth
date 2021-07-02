<div id="panel-{{ $type }}" class="card h-100">

    @if($type == 'sender')
    <div class="card-header font-weight-bold pt-1 pb-1 bg-warning">
        @else
        <div class="card-header font-weight-bold pt-1 pb-1">
            @endif

            {{ ucfirst($type) }}
            <span class="fas fa-info-circle" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="Enter the {{ucfirst($type)}}'s details within this panel. Provide accurate information for all fields."></span>

            <div class="float-right">
                <button type="button" id="button-{{ $type == 'sender' ? 'recipient' : 'sender' }}" class="btn btn-outline-secondary btn-sm btn-xs">Show {{$type == 'sender' ? 'Recipient' : 'Sender'}}</button>
                @if(!isset($formView))
                <button type="button" id="button-{{ $type }}-address-book" class="btn btn-outline-secondary ml-md-2 btn-sm btn-xs" data-toggle="modal" data-target="#address_book"><span class="far fa-address-book" aria-hidden="true"></span> Address Book</button>
                @endif
            </div>

        </div>

        <div class="card-body pt-2 pb-2 text-nowrap">

            @if($type=='recipient' && Auth::user()->hasMultipleCompanies() && !isset($shipment))

            <div class="form-group row">

                <label class="col-md-3 col-form-label">
                    Shipper: <abbr title="This information is required.">*</abbr>
                </label>

                <div class="col-md-9">
                    {!! Form::select('company_id', dropDown('enabledSites', 'Please select'), old('company_id'), array('id' => 'company_id', 'class' =>'form-control form-control-sm')) !!}
                </div>
            </div>
            <hr class="mt-2 mb-3">
            @endif

            {!! Form::hidden($type . '_id',  old($type . '_id'), array('id' => $type . '_id')) !!}

            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Name: <abbr title="This information is required.">*</abbr>
                </label>

                <div class="col-md-9">
                    {!! Form::Text($type . '_name', old($type . '_name'), ['id' => $type . '_name', 'class' =>'form-control form-control-sm', 'maxlength' => 35]) !!}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label">Company:</label>

                <div class="col-md-9">
                    {!! Form::Text($type . '_company_name', old($type . '_company_name'), ['id' => $type . '_company_name', 'class' =>'form-control form-control-sm', 'maxlength' => 35]) !!}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Type: <abbr title="This information is required.">*</abbr>
                </label>

                <div class="col-md-9">
                    {!! Form::select($type . '_type', dropDown('type', 'Please select'), old($type . '_type'), array('id' => $type . '_type', 'class' =>'form-control form-control-sm')) !!}
                </div>
            </div>

            <hr class="mt-2 mb-3">

            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Address 1: <abbr title="This information is required.">*</abbr>
                </label>

                <div class="col-md-9">
                    {!! Form::Text($type . '_address1', old($type . '_address1'), ['id' => $type . '_address1', 'class' =>'form-control form-control-sm', 'maxlength' => 35]) !!}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Address 2:
                </label>

                <div class="col-md-9">
                    {!! Form::Text($type . '_address2', old($type . '_address2'), ['id' => $type . '_address2', 'class' =>'form-control form-control-sm', 'maxlength' => 35]) !!}
                </div>
            </div>

            @if($type == 'sender')
            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Address 3:
                </label>

                <div class="col-md-9">
                    {!! Form::Text($type . '_address3', old($type . '_address3'), ['id' => $type . '_address3', 'class' =>'form-control form-control-sm', 'maxlength' => 35]) !!}
                </div>
            </div>
            @else
            {!! Form::hidden($type . '_address3',  old($type . '_address3'), array('id' => $type . '_address3')) !!}
            @endif

            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    City: <abbr title="This information is required.">*</abbr>
                </label>

                <div class="col-md-9">
                    {!! Form::Text($type . '_city', old($type . '_city'), ['id' => $type . '_city', 'class' =>'form-control form-control-sm', 'maxlength' => 35]) !!}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Country: <abbr title="This information is required.">*</abbr>
                </label>

                <div class="col-md-9">
                    @if($type == 'sender')
                    {!! Form::select($type . '_country_code', dropDown('senderCountries'), old($type . '_country_code'), array('id' => $type . '_country_code', 'class' =>'form-control form-control-sm')) !!}
                    @else
                    {!! Form::select($type . '_country_code', dropDown('countries', 'Please select'), old($type . '_country_code'), array('id' => $type . '_country_code', 'class' =>'form-control form-control-sm')) !!}
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    State/ County:
                </label>

                <div class="col-md-9" id="{{$type }}-state-placeholder">
                    {!! Form::Text($type . '_state', old($type . '_state'), ['id' => $type . '_state', 'class' =>'form-control form-control-sm', 'maxlength' => 35]) !!}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Postcode: <abbr title="This information is required.">*</abbr>
                </label>

                <div class="col-md-9">
                    {!! Form::Text($type . '_postcode', old($type . '_postcode'), ['id' => $type . '_postcode', 'class' =>'form-control form-control-sm', 'maxlength' => 10]) !!}
                </div>
            </div>

            <hr class="mt-2 mb-3">

            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Telephone: <abbr title="This information is required.">*</abbr>
                </label>

                <div class="col-md-9">
                    {!! Form::Text($type . '_telephone', old($type . '_telephone'), ['id' => $type . '_telephone', 'class' =>'form-control form-control-sm', 'maxlength' => 25]) !!}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Email: @if($type == 'sender') <abbr title="This information is required.">*</abbr> @endif
                </label>

                <div class="col-md-9">
                    {!! Form::Text($type . '_email', old($type . '_email'), ['id' => $type . '_email', 'class' =>'form-control form-control-sm', 'maxlength' => 50]) !!}
                </div>
            </div>

            @if($type == 'recipient')

            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Account #:
                </label>

                <div class="col-md-9">
                    {!! Form::Text($type . '_account_number', old($type . '_account_number'), ['id' => $type . '_account_number', 'class' =>'form-control form-control-sm', 'maxlength' => 15]) !!}
                </div>
            </div>

            @endif

            @if(!isset($formView))
            <div class="form-group row">
                <div class="col-md-3 col-form-label">&nbsp;</div>
                <div class="col-md-4">
                    <button id="save-{{$type }}-address" class="btn btn-outline-secondary btn-sm btn-xs" type="button">Save Address</button>
                </div>
                <div class="col-md-3">
                    <button id="clear-{{$type }}-address" class="btn btn-outline-secondary btn-sm btn-xs" type="button">Clear Address</button>
                </div>
            </div>
            @endif

        </div>
    </div>
