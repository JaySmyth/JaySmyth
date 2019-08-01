@extends('layouts.app')

@section('content')

    @if(isset($shipment))

        @if(isset($formView))
            <h3 class="mt-3 float-left">Shipment - {{ $shipment->consignment_number }}
                <small class="ml-sm-3 text-muted">
                    <span class="fas fa-window-restore mr-sm-1" aria-hidden="true"></span> Form View
                </small>
            </h3>
            <div class="clearfix"></div>
        @else
            <h3 class="mt-3 float-left text-secondary">
                <span class="fas fa-floppy-o mr-sm-1" aria-hidden="true"></span> Saved Shipment
                <small>{{ $shipment->consignment_number }}</small>
            </h3>
        @endif

    @else
        <h3 id="form_title" class="mt-3 float-left">Create Shipment</h3>
    @endif

    {!! Form::hidden('sender-panel-validator', 0, array('id' => 'sender-panel-validator')) !!}
    {!! Form::hidden('recipient-panel-validator', 0, array('id' => 'recipient-panel-validator')) !!}

    {!! Form::Open(['id' => 'create-shipment', 'url' => 'shipments', 'class' => 'form-compact-xs', 'autocomplete' => 'off']) !!}

    <div id="shipment">

        @if(!isset($formView))
            <div class="float-right mb-2">
                <button id="button-proceed" class="ml-md-4 btn btn-primary btn-lg btn-shadow" type="button">Proceed to Final Step
                    <span class="fas fa-chevron-right" aria-hidden="true"></span></button>
            </div>
            <div class="clearfix"></div>
        @endif

        @if(isset($formView))
            {!! Form::hidden('form_view', 1, array('id' => 'form_view', 'disabled')) !!}
        @endif

        {!! Form::hidden('form_values', "", array('id' => 'form_values')) !!}
        {!! Form::hidden('address_book_definition', null, array('id' => 'address_book_definition', 'disabled')) !!}

    <!-- Saved Shipment -->
        {!! Form::hidden('shipment_id', (isset($shipment)) ? $shipment->id : false, array('id' => 'shipment_id')) !!}

    <!-- User Specific -->
        {!! Form::hidden('user_id', Auth::user()->id, array('id' => 'user_id')) !!}
        {!! Form::hidden('print_formats_id', Auth::user()->printFormat->id, array('id' => 'print_formats_id')) !!}

    <!-- Mode -->
        {!! Form::hidden('mode', $mode->name, array('id' => 'mode')) !!}
        {!! Form::hidden('mode_id', $mode->id, array('id' => 'mode_id')) !!}

    <!-- Company ID -->
        @if(!Auth::user()->hasMultipleCompanies() || isset($shipment))
            {!! Form::hidden('company_id', (isset($shipment)) ? $shipment->company_id : Auth::user()->company_id, array('id' => 'company_id')) !!}
        @endif

    <!-- Localisations -->
        {!! Form::hidden('dims_uom', (isset($arrays['localisation']['dims_uom'])) ? $arrays['localisation']['dims_uom'] : 'cm', array('id' => 'dims_uom')) !!}
        {!! Form::hidden('weight_uom', (isset($arrays['localisation']['weight_uom'])) ? $arrays['localisation']['weight_uom'] : 'kg', array('id' => 'weight_uom')) !!}
        {!! Form::hidden('date_format', (isset($arrays['localisation']['date_format'])) ? $arrays['localisation']['date_format'] : 'dd-mm-yyyy', array('id' => 'date_format')) !!}
        {!! Form::hidden('currency_code', (isset($arrays['localisation']['currency_code'])) ? $arrays['localisation']['currency_code'] : 'GBP', array('id' => 'currency_code')) !!}

    <!-- Miscellaneous -->
        {!! Form::hidden('weight', null, array('id' => 'weight')) !!}
        {!! Form::hidden('service_id', null, array('id' => 'service_id')) !!}
        {!! Form::hidden('shipping_charge', null, array('id' => 'shipping_charge')) !!}
        {!! Form::hidden('data_loaded', old('data_loaded', 'false'), array('id' => 'data_loaded')) !!}

    <!-- Customs Value -->
        {!! Form::hidden('customs_value', null, array('id' => 'customs_value')) !!}
        {!! Form::hidden('customs_value_currency_code', null, array('id' => 'customs_value_currency_code')) !!}

    <!-- Main Form -->
        {!! Form::hidden('commodity_count', old('commodity_count', 0), array('id' => 'commodity_count')) !!}

        <div class="row mb-4">

            <div class="col-md-4">
                @if(isset($formView))
                    @include('shipments.partials.address', ['type' => 'sender', 'formView' => true])
                    @include('shipments.partials.address', ['type' => 'recipient', 'formView' => true])
                @else
                    @include('shipments.partials.address', ['type' => 'sender'])
                    @include('shipments.partials.address', ['type' => 'recipient'])
                @endif
            </div>

            <div class="col-md-8">

                <!-- ==================================================================================================================================================== -->
                <!-- ==================================================================================================================================================== -->

                <div id="panel-details" class="card mb-2">

                    <div class="card-header font-weight-bold pt-1 pb-1 font-weight-bold pt-1 pb-1 clearfix">
                        Shipment Details
                        @if(!isset($formView))
                            <button id="set-preferences" class="btn btn-outline-secondary btn-sm btn-xs float-right ml-md-3" type="button">Set Defaults</button>
                            <button id="reset-preferences" class="btn btn-outline-secondary btn-sm btn-xs float-right ml-md-3" type="button">Reset Defaults</button>
                            <button id="button-save-shipment" class="btn btn-secondary btn-sm btn-xs float-right" type="button">
                                <span class="far fa-save" aria-hidden="true"></span> Save Shipment
                            </button>
                        @endif
                    </div>

                    <div class="card-body pt-2 pb-2 mt-2 text-nowrap">
                        <div class="row">

                            <div class="col-md-5">
                                <div class="form-group row">
                                    <label class="col-md-5 col-form-label">
                                        Pieces: <abbr title="This information is required.">*</abbr>
                                    </label>
                                    <div class="col-md-7">
                                        {!! Form::Text('pieces', old('pieces') ?? 1, ['id' => 'pieces', 'class' => 'form-control form-control-sm numeric-only-required', 'maxlength' => '2']) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-5 col-form-label">
                                        Ship Reference / PO: <abbr title="This information is required.">*</abbr>
                                    </label>
                                    <div class="col-md-7">
                                        {!! Form::Text('shipment_reference', old('shipment_reference'), ['id' => 'shipment_reference', 'class' =>'form-control form-control-sm', 'maxlength' => '30']) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-5 col-form-label">
                                        Ship Reason:
                                        <abbr title="This information is required for Commercial Invoices.">*</abbr>
                                    </label>
                                    <div class="col-md-7">
                                        {!! Form::select('ship_reason', dropDown('shipReasons',null,'sold'), old('ship_reason'), array('id' => 'ship_reason', 'class' =>'form-control form-control-sm')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group row">
                                    <label class="col-md-5 col-form-label">
                                        Collection Date: <abbr title="This information is required.">*</abbr>
                                    </label>
                                    <div class="col-md-7">
                                        {!! Form::Text('collection_date', old('collection_date'), ['id' => 'collection_date', 'class' =>'form-control form-control-sm bg-white', 'maxlength' => '10', 'readonly' => 'readonly']) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-5 col-form-label">
                                        Hazardous: <abbr title="This information is required.">*</abbr>
                                    </label>
                                    <div class="col-md-7">
                                        {!! Form::select('hazardous', dropDown('hazards', null, $mode->id), old('hazardous'), array('id' => 'hazardous', 'class' =>'form-control form-control-sm')) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-5 col-form-label">Instructions:</label>
                                    <div class="col-md-7">
                                        {!! Form::Text('special_instructions', old('special_instructions'), ['id' => 'special_instructions', 'class' =>'form-control form-control-sm', 'maxlength' => '100']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="btn-group-vertical btn-group-sm float-right" role="group" aria-label="">
                                    <button id="button-alerts" type="button" class="btn btn-outline-secondary">Alerts</button>
                                    @can('courier_billing')
                                        <button id="button-billing" type="button" class="btn btn-outline-secondary">Billing</button>@endcan
                                    @can('courier_broker')
                                        <button id="button-broker" type="button" class="btn btn-outline-secondary">Broker</button>@endcan
                                    <button id="button-invoice" type="button" class="btn btn-outline-secondary">Customs</button>
                                    <button id="button-options" type="button" class="btn btn-outline-secondary">Options</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ==================================================================================================================================================== -->
                <!-- ==================================================================================================================================================== -->

                <div id="panel-alerts" class="card mb-2">

                    <div class="card-header font-weight-bold pt-1 pb-1">Alerts
                        <span class="fas fa-info-circle" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="This panel allows you to define the email alerts to be generated for your shipment. You MUST enter valid sender/recipient email addresse and tick the desired alerts. To send email alerts to an additional email address, enter a valid email address into the 'other' field."></span>
                        <div class="float-right">
                            <button id="button-alerts-return" class="btn btn-primary btn-sm btn-xs" type="button">Return</button>
                        </div>
                    </div>

                    <div class="card-body pt-2 pb-2 text-nowrap">

                        <div class="row font-weight-bold">
                            <div class="col-md-7 text-center">&nbsp;</div>
                            <div class="col-md-1 text-center"><abbr title="Despatched">Desp</abbr></div>
                            <div class="col-md-1 text-center"><abbr title="Out For Delivery">OFD</abbr></div>
                            <div class="col-md-1 text-center"><abbr title="Delivered">Del</abbr></div>
                            <div class="col-md-1 text-center"><abbr title="Cancelled">Canc</abbr></div>
                            <div class="col-md-1 text-center"><abbr title="Delivery Exceptions">Excp</abbr></div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-1">Sender</div>
                            <div class="col-md-6">{!! Form::Text('display_sender_email', old('display_sender_email'), ['id' => 'display_sender_email', 'class' => 'form-control form-control-sm', 'placeholder' => "Provide sender's email address", 'readonly']) !!}</div>
                            <div class="col-md-1 text-center">{!! Form::checkbox('alerts[sender][despatched]', 1, old('alerts[sender][despatched]'), ['id' => 'alerts-sender-despatched']) !!}</div>
                            <div class="col-md-1 text-center">{!! Form::checkbox('alerts[sender][out_for_delivery]', 1, old('alerts[sender][out_for_delivery]'), ['id' => 'alerts-sender-out-for-delivery']) !!}</div>
                            <div class="col-md-1 text-center">{!! Form::checkbox('alerts[sender][delivered]', 1, old('alerts[sender][delivered]'), ['id' => 'alerts-sender-delivered']) !!}</div>
                            <div class="col-md-1 text-center">{!! Form::checkbox('alerts[sender][cancelled]', 1, old('alerts[sender][cancelled]'), ['id' => 'alerts-sender-cancelled']) !!}</div>
                            <div class="col-md-1 text-center">{!! Form::checkbox('alerts[sender][problems]', 1, old('alerts[sender][problems]'), ['id' => 'alerts-sender-problems']) !!}</div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-1">Recipient</div>
                            <div class="col-md-6">{!! Form::Text('display_recipient_email', old('display_recipient_email'), ['id' => 'display_recipient_email', 'class' => 'form-control form-control-sm', 'placeholder' => "Provide recipient's email address", 'readonly']) !!}</div>
                            <div class="col-md-1 text-center">{!! Form::checkbox('alerts[recipient][despatched]', 1, old('alerts[recipient][despatched]'), ['id' => 'alerts-recipient-despatched']) !!}</div>
                            <div class="col-md-1 text-center">{!! Form::checkbox('alerts[recipient][out_for_delivery]', 1, old('alerts[recipient][out_for_delivery]'), ['id' => 'alerts-recipient-out-for-delivery']) !!}</div>
                            <div class="col-md-1 text-center">{!! Form::checkbox('alerts[recipient][delivered]', 1, old('alerts[recipient][delivered]'), ['id' => 'alerts-recipient-delivered']) !!}</div>
                            <div class="col-md-1 text-center">{!! Form::checkbox('alerts[recipient][cancelled]', 1, old('alerts[recipient][cancelled]'), ['id' => 'alerts-recipient-cancelled']) !!}</div>
                            <div class="col-md-1 text-center">{!! Form::checkbox('alerts[recipient][problems]', 1, old('alerts[recipient][problems]'), ['id' => 'alerts-recipient-problems']) !!}</div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-1">Broker</div>
                            <div class="col-md-6">{!! Form::Text('display_broker_email', old('display_broker_email'), ['id' => 'display_broker_email', 'class' => 'form-control form-control-sm', 'placeholder' => "Provide broker's email address", 'readonly']) !!}</div>
                            <div class="col-md-1 text-center">{!! Form::checkbox('alerts[broker][despatched]', 1, old('alerts[broker][despatched]'), ['id' => 'alerts-broker-despatched']) !!}</div>
                            <div class="col-md-1 text-center">{!! Form::checkbox('alerts[broker][out_for_delivery]', 1, old('alerts[broker][out_for_delivery]'), ['id' => 'alerts-broker-out-for-delivery']) !!}</div>
                            <div class="col-md-1 text-center">{!! Form::checkbox('alerts[broker][delivered]', 1, old('alerts[broker][delivered]'), ['id' => 'alerts-broker-delivered']) !!}</div>
                            <div class="col-md-1 text-center">{!! Form::checkbox('alerts[broker][cancelled]', 1, old('alerts[broker][cancelled]'), ['id' => 'alerts-broker-cancelled']) !!}</div>
                            <div class="col-md-1 text-center">{!! Form::checkbox('alerts[broker][problems]', 1, old('alerts[broker][problems]'), ['id' => 'alerts-broker-problems']) !!}</div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-1">Other</div>
                            <div class="col-md-6">{!! Form::Text('other_email', old('other_email'), ['id' => 'other_email', 'class' => 'form-control form-control-sm', 'placeholder' => 'Email address']) !!}</div>
                            <div class="col-md-1 text-center">{!! Form::checkbox('alerts[other][despatched]', 1, old('alerts[other][despatched]'), ['id' => 'alerts-other-despatched']) !!}</div>
                            <div class="col-md-1 text-center">{!! Form::checkbox('alerts[other][out_for_delivery]', 1, old('alerts[other][out_for_delivery]'), ['id' => 'alerts-other-out-for-delivery']) !!}</div>
                            <div class="col-md-1 text-center">{!! Form::checkbox('alerts[other][delivered]', 1, old('alerts[other][delivered]'), ['id' => 'alerts-other-delivered']) !!}</div>
                            <div class="col-md-1 text-center">{!! Form::checkbox('alerts[other][cancelled]', 1, old('alerts[other][cancelled]'), ['id' => 'alerts-other-cancelled']) !!}</div>
                            <div class="col-md-1 text-center">{!! Form::checkbox('alerts[other][problems]', 1, old('alerts[other][problems]'), ['id' => 'alerts-other-problems']) !!}</div>
                        </div>

                    </div>
                </div>


                <!-- ==================================================================================================================================================== -->
                <!-- ==================================================================================================================================================== -->


                <div id="panel-billing" class="card mb-2">
                    <div class="card-header font-weight-bold pt-1 pb-1">Billing
                        <span class="fas fa-info-circle" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="This panel allows you to define custom billing information. Any account numbers provided must be valid carrier account numbers for the service to be selected."></span>
                        <div class="float-right">
                            <button id="button-billing-return" class="btn btn-primary btn-sm btn-xs" type="button">Return</button>
                        </div>
                    </div>
                    <div class="row">&nbsp;</div>
                    <div class="card-body pt-2 pb-2 text-nowrap">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::Label('Bill Shipping To:', null, array('class' => 'col-md-5 col-form-label')) !!}
                                    <div class="col-md-7">
                                        {!! Form::select('bill_shipping', array('sender' => 'Sender', 'recipient' => 'Recipient', 'other' => 'Other'), old('bill_shipping'), array('id' => 'bill_shipping', 'class' =>'form-control form-control-sm')) !!}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    {!! Form::Label('Bill Tax and Duty To:', null, array('class' => 'col-md-5 col-form-label')) !!}
                                    <div class="col-md-7">
                                        {!! Form::select('bill_tax_duty', array('sender' => 'Sender', 'recipient' => 'Recipient', 'other' => 'Other'), old('bill_tax_duty'), array('id' => 'bill_tax_duty', 'class' =>'form-control form-control-sm')) !!}
                                    </div>
                                </div>
                                <br>
                            <!--
                            <div class="form-group row">
                                {!! Form::Label('Carrier:', null, array('class' => 'col-md-5 col-form-label')) !!}
                                    <div class="col-md-7">
{!! Form::select('carrier_id', dropDown('carriers', 'Please select'), old('carrier_id'), array('id' => 'carrier_id', 'class' =>'form-control form-control-sm')) !!}
                                    </div>
                                </div>
-->
                            </div>
                            <div class="col-md-5">
                                <div class="form-group row">
                                    {!! Form::Label('Account#', null, array('class' => 'col-md-3 col-form-label')) !!}
                                    <div class="col-md-9">
                                        {!! Form::Text('bill_shipping_account', old('bill_shipping_account'), ['id' => 'bill_shipping_account', 'class' =>'form-control form-control-sm', 'maxlength' => '15']) !!}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    {!! Form::Label('Account#', null, array('class' => 'col-md-3 col-form-label')) !!}
                                    <div class="col-md-9">
                                        {!! Form::Text('bill_tax_duty_account', old('bill_tax_duty_account'), ['id' => 'bill_tax_duty_account', 'class' =>'form-control form-control-sm', 'maxlength' => '15']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- ==================================================================================================================================================== -->
                <!-- ==================================================================================================================================================== -->

                @can('courier_broker')
                    <div id="panel-broker" class="card mb-2">
                        <div class="card-header font-weight-bold pt-1 pb-1">Broker
                            <span class="fas fa-info-circle" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="This panel allows you to specify a 3rd party for customs clearance purposes."></span>
                            <div class="float-right">
                                <button id="button-broker-return" class="btn btn-primary btn-sm btn-xs" type="button">Return</button>
                            </div>
                        </div>

                        <div class="card-body pt-2 pb-2 text-nowrap">
                            <div id="broker-fieldset" class="row">

                                <div class="col-md-4">
                                    <div class="form-group row">
                                        {!! Form::Label('Company:', null, array('class' => 'col-md-4 col-form-label')) !!}
                                        <div class="col-md-8">
                                            {!! Form::Text('broker[company]', old('broker[company]'), ['id' => 'broker-company', 'class' =>'form-control form-control-sm', 'maxlength' => '100']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::Label('Contact:', null, array('class' => 'col-md-4 col-form-label')) !!}
                                        <div class="col-md-8">
                                            {!! Form::Text('broker[contact]', old('broker[contact]'), ['id' => 'broker-contact', 'class' =>'form-control form-control-sm', 'maxlength' => '30']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::Label('Add. 1:', null, array('class' => 'col-md-4 col-form-label')) !!}
                                        <div class="col-md-8">
                                            {!! Form::Text('broker[address1]', old('broker[address1]'), ['id' => 'broker-address1', 'class' =>'form-control form-control-sm', 'maxlength' => '100']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::Label('Add. 2:', null, array('class' => 'col-md-4 col-form-label')) !!}
                                        <div class="col-md-8">
                                            {!! Form::Text('broker[address2]', old('broker[address2]'), ['id' => 'broker-address2', 'class' =>'form-control form-control-sm', 'maxlength' => '100']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">

                                    <div class="form-group row">
                                        {!! Form::Label('City:', null, array('class' => 'col-md-4 col-form-label')) !!}
                                        <div class="col-md-8">
                                            {!! Form::Text('broker[city]', old('broker[city]'), ['id' => 'broker-city', 'class' =>'form-control form-control-sm', 'maxlength' => '30']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::Label('Country:', null, array('class' => 'col-md-4 col-form-label')) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('broker[country_code]', dropDown('countries', 'Please select'), old('broker[country_code]'), array('id' => 'broker-country-code', 'class' =>'form-control form-control-sm')) !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::Label('State:', null, array('class' => 'col-md-4 col-form-label')) !!}
                                        <div class="col-md-8">
                                            {!! Form::Text('broker[state]', old('broker[state]'), ['id' => 'broker-state', 'class' =>'form-control form-control-sm', 'maxlength' => '30']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::Label('Postcode:', null, array('class' => 'col-md-4 col-form-label')) !!}
                                        <div class="col-md-8">
                                            {!! Form::Text('broker[postcode]', old('broker[postcode]'), ['id' => 'broker-postcode', 'class' =>'form-control form-control-sm', 'maxlength' => '15']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">

                                    <div class="form-group row">
                                        {!! Form::Label('Telephone:', null, array('class' => 'col-md-4 col-form-label')) !!}
                                        <div class="col-md-8">
                                            {!! Form::Text('broker[telephone]', old('broker[telephone]'), ['id' => 'broker-telephone', 'class' =>'form-control form-control-sm', 'maxlength' => '15']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::Label('Email:', null, array('class' => 'col-md-4 col-form-label')) !!}
                                        <div class="col-md-8">
                                            {!! Form::Text('broker[email]', old('broker[email]'), ['id' => 'broker-email', 'class' =>'form-control form-control-sm', 'maxlength' => '100']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::Label('Broker ID:', null, array('class' => 'col-md-4 col-form-label')) !!}
                                        <div class="col-md-8">
                                            {!! Form::Text('broker[id]', old('broker[id]'), ['id' => 'broker-id', 'class' =>'form-control form-control-sm', 'maxlength' => '15']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::Label('Account#:', null, array('class' => 'col-md-4 col-form-label')) !!}
                                        <div class="col-md-8">
                                            {!! Form::Text('broker[account]', old('broker[account]'), ['id' => 'broker-account', 'class' =>'form-control form-control-sm', 'maxlength' => '15']) !!}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
            @endcan

            <!-- ==================================================================================================================================================== -->
                <!-- ==================================================================================================================================================== -->

                <div id="panel-invoice" class="card mb-2">

                    <div class="card-header font-weight-bold pt-1 pb-1">Customs / Commercial Invoice
                        <div class="float-right">
                            <button id="button-invoice-return" class="btn btn-primary btn-sm btn-xs" type="button">Return</button>
                        </div>
                    </div>

                    <div class="card-body pt-2 pb-2 mt-3 text-nowrap">

                        <div class="row">

                            <div class="col-md-5">
                                <div class="form-group row">
                                    {!! Form::Label('Invoice Type:', null, array('class' => 'col-md-5 col-form-label')) !!}
                                    <div class="col-md-7">
                                        {!! Form::select('invoice_type', ['c' => 'Commercial', 'p' => 'Proforma'], old('invoice_type'), array('id' => 'invoice_type', 'class' =>'form-control form-control-sm')) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::Label('Terms of Sale:', null, array('class' => 'col-md-5 col-form-label')) !!}
                                    <div class="col-md-7">
                                        {!! Form::select('terms_of_sale', dropDown('terms', 'Please Select Terms', $mode->id), old('terms_of_sale'), array('id' => 'terms_of_sale', 'class' =>'form-control form-control-sm')) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::Label('EORI:', null, array('class' => 'col-md-5 col-form-label')) !!}
                                    <div class="col-md-7">
                                        {!! Form::Text('eori', old('eori'), ['id' => 'eori', 'class' =>'form-control form-control-sm']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="form-group row">
                                    {!! Form::Label('Ultimate Destination:', null, array('class' => 'col-md-5 col-form-label')) !!}
                                    <div class="col-md-7">
                                        {!! Form::select('ultimate_destination_country_code', dropDown('countries', 'Please select'), old('ultimate_destination_country_code'), array('id' => 'ultimate_destination_country_code', 'class' =>'form-control form-control-sm')) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::Label('Invoice Comments:', null, array('class' => 'col-md-5 col-form-label')) !!}
                                    <div class="col-md-7">
                                        {!! Form::Text('commercial_invoice_comments', old('commercial_invoice_comments'), ['id' => 'commercial_invoice_comments', 'class' =>'form-control form-control-sm', 'maxlength' => '100']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================================================================================================================================================== -->
                <!-- ==================================================================================================================================================== -->

                <div id="panel-options" class="card mb-2">
                    <div class="card-header font-weight-bold pt-1 pb-1">Options
                        <span class="fas fa-info-circle" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="This panel allows you to specify any special requirements or miscellaneous options for your shipment."></span>
                        <div class="float-right">
                            <button id="button-options-return" class="btn btn-primary btn-sm btn-xs" type="button">Return</button>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-2 text-nowrap">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group row">
                                    <label class="col-md-5 col-form-label">Value for Insurance</label>
                                    <div class="col-md-6">
                                        {!! Form::Text('insurance_value', old('insurance_value'), ['id' => 'insurance_value', 'class' =>'form-control form-control-sm numeric-only', 'maxlength' => '6']) !!}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-5 col-form-label">Lithium Batteries/Cells</label>
                                    <div class="col-md-6">
                                        {!! Form::select('lithium_batteries', ['' => 'None', 1 => 'Ion Packed with Equipment (UN3481, PI966)', 2 => 'Ion Contained in Equipment (UN3481, PI967)', 3 => 'Metal Packed with Equipment (UN3091, PI969)', 4 => 'Metal Contained in Equipment (UN3091, PI970)'], old('lithium_batteries'), array('id' => 'lithium_batteries', 'class' =>'form-control form-control-sm')) !!}
                                    </div>
                                </div>

                                <fieldset id="dry-ice-fieldset">

                                    @foreach($arrays['packages'] as $key => $val)

                                        <div class="form-group row item">
                                            <label class="col-md-5 col-form-label">Dry Ice Weight (Pkg
                                                <span class="package-number">{{$loop->iteration}}</span>)</label>
                                            <div class="col-md-6">
                                                <div class="input-group input-group-sm">
                                                    {!! Form::Text('packages['.$key.'][dry_ice_weight]', (isset($val['dry_ice_weight'])) ? $val['dry_ice_weight']: null, ['id' => 'packages-'.$key.'-dry-ice-weight', 'class' => 'form-control form-control-sm decimal-only', 'maxlength' => 5]) !!}
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">kg</span></div>
                                                </div>
                                            </div>
                                        </div>

                                    @endforeach

                                </fieldset>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group row">
                                    <label class="col-md-5 col-form-label">Alcohol</label>
                                    <div class="col-md-7">
                                        {!! Form::select('alcohol[type]', array('' => 'None', 'A' => 'Ale', 'B' => 'Beer', 'L' => 'Light Wine', 'S' => 'Distilled Spirits', 'W' => 'Wine'), old('alcohol[type]'), array('id' => 'alcohol-type', 'class' =>'form-control form-control-sm')) !!}
                                    </div>
                                </div>

                                <fieldset id="alcohol-fieldset">
                                    <div class="form-group row">
                                        <label class="col-md-5 col-form-label">Alcohol Packaging</label>
                                        <div class="col-md-7">
                                            {!! Form::select('alcohol[packaging]', array('BL' => 'Barrel', 'BT' => 'Bottle', 'CN' => 'Carton', 'CS' => 'Case'), old('alcohol[packaging]'), array('id' => 'alcohol-packaging', 'class' =>'form-control form-control-sm')) !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-5 col-form-label">Alcohol Volume (L)</label>
                                        <div class="col-md-7">
                                            {!! Form::Text('alcohol[volume]', old('alcohol[volume]'), ['id' => 'alcohol-volume', 'class' => 'form-control form-control-sm numeric-only', 'maxlength' => '8']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-5 col-form-label">Alcohol Quantity</label>
                                        <div class="col-md-7">
                                            {!! Form::Text('alcohol[quantity]', old('alcohol[quantity]'), ['id' => 'alcohol-quantity', 'class' => 'form-control form-control-sm numeric-only', 'maxlength' => '6']) !!}
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================================================================================================================================================== -->
                <!-- ==================================================================================================================================================== -->

                <div id="panel-packages" class="card mb-2">

                    <div class="card-header font-weight-bold pt-1 pb-1">

                        @if(isset($formView))

                            Package Details
                            <span class="fas fa-info-circle" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="This panel allows you to define the weights and dimensions of your packages. If you have more than one package, the duplicate button will 'fill down' the weight and dimensions of the first package. To add or remove packages, change the number in the 'Pieces' field above. You must provide the correct weight and dimensions for every package."></span>

                        @else

                            <div class="d-flex justify-content-between">
                                <div>Package Details
                                    <span class="fas fa-info-circle" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="This panel allows you to define the weights and dimensions of your packages. If you have more than one package, the duplicate button will 'fill down' the weight and dimensions of the first package. To add or remove packages, change the number in the 'Pieces' field above. You must provide the correct weight and dimensions for every package."></span>
                                </div>
                                <div>
                                    <div id="largest_girth" class="font-italic font-weight-normal text-muted"></div>
                                </div>
                                <div>
                                    <button id="button-duplicate" class="btn btn-outline-secondary btn-sm btn-xs" type="button">Duplicate</button>
                                </div>
                            </div>

                        @endif
                    </div>

                    <div id="container-packages" class="card-body pt-2 pb-2 text-nowrap">

                        <div class="row font-weight-bold text-medium">
                            <div class="col-md-1">#</div>
                            <div class="col-md-3">Packaging Type</div>
                            <div class="col-md-2">Weight</div>
                            <div class="col-md-2">Length</div>
                            <div class="col-md-2">Width</div>
                            <div class="col-md-2">Height</div>
                        </div>

                        @foreach($arrays['packages'] as $key => $val)
                            <div id="package-{!! $key !!}" class="row item mb-1">
                                <div class="col-md-1 font-weight-bold pt-1 package-number">
                                    {!!$key + 1!!}
                                </div>
                                <div class="col-md-3">
                                    {!! Form::select('packages['.$key.'][packaging_code]', $arrays['packaging'], (isset($val['packaging_code'])) ? $val['packaging_code']: null, array('id' => 'packages-'.$key.'-packaging-code', 'class' => 'form-control form-control-sm packaging-type')) !!}
                                </div>

                                <div class="col-md-2">
                                    <div class="input-group input-group-sm">
                                        {!! Form::Text('packages['.$key.'][weight]', (isset($val['weight'])) ? $val['weight']: null, ['id' => 'packages-'.$key.'-weight', 'class' => 'form-control form-control-sm decimal-only-required', 'maxlength' => 6]) !!}
                                        <div class="input-group-append">
                                            <span class="input-group-text localisation-weight">{{$arrays['localisation']['weight_uom'] ?? 'kg'}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="input-group input-group-sm">
                                        {!! Form::Text('packages['.$key.'][length]', (isset($val['length'])) ? $val['length']: null, ['id' => 'packages-'.$key.'-length', 'class' => 'form-control form-control-sm numeric-only-required dim-input', 'maxlength' => 3]) !!}
                                        <div class="input-group-append">
                                            <span class="input-group-text localisation-dims">{{$arrays['localisation']['dims_uom'] ?? 'cm'}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group input-group-sm">
                                        {!! Form::Text('packages['.$key.'][width]', (isset($val['width'])) ? $val['width']: null, ['id' => 'packages-'.$key.'-width', 'class' => 'form-control form-control-sm numeric-only-required dim-input', 'maxlength' => 3]) !!}
                                        <div class="input-group-append">
                                            <span class="input-group-text localisation-dims">{{$arrays['localisation']['dims_uom'] ?? 'cm'}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group input-group-sm">
                                        {!! Form::Text('packages['.$key.'][height]', (isset($val['height'])) ? $val['height']: null, ['id' => 'packages-'.$key.'-height', 'class' => 'form-control form-control-sm numeric-only-required dim-input', 'maxlength' => 3]) !!}
                                        <div class="input-group-append">
                                            <span class="input-group-text localisation-dims">{{$arrays['localisation']['dims_uom'] ?? 'cm'}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                <!-- ==================================================================================================================================================== -->
                <!-- ==================================================================================================================================================== -->

                <div id="panel-contents" class="card">
                    <div class="card-header font-weight-bold pt-1 pb-1">
                        Shipment Contents
                        <span class="fas fa-info-circle" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="This panel allows you to define what is contained within each of your packages. There MUST be at least one line for every package. For shipments with multiple packages but only one commodity, use the 'Fill' button to autocomplete the package contents for you (the 'Fill' button is only displayed when appropriate)."></span>
                        <div class="float-right">
                            <button type="button" id="button-add-commodity" class="btn btn-outline-secondary btn-sm btn-xs" data-toggle="modal" data-target="#commodities">Add Commodity</button>
                        </div>
                    </div>
                    <div id="container-contents" class="card-body pt-2 pb-2 text-nowrap">
                        <div id="commodity-headings" class="row font-weight-bold">
                            <div class="col-3">Description</div>
                            <div class="col-2">Product Code</div>
                            <div class="col-2">Quantity</div>
                            <div class="col-2">Unit Weight</div>
                            <div class="col-2">Unit Value</div>
                            <div class="col-1">&nbsp;</div>
                        </div>

                        @foreach($arrays['contents'] as $key => $val)
                            <div id="commodity-{!! $key !!}" class="form-group row item">

                                <div class="col-3">
                                    {!! Form::Text('contents['.$key.'][description]', (isset($val['description'])) ? $val['description']: null, ['id' => 'contents-'.$key.'-description', 'class' => 'form-control form-control-sm', 'readonly']) !!}
                                </div>

                                <div class="col-2">
                                    {!! Form::Text('contents['.$key.'][product_code]', (isset($val['product_code'])) ? $val['product_code']: null, ['id' => 'contents-'.$key.'-product-code', 'class' => 'form-control form-control-sm', 'readonly']) !!}
                                </div>

                                <div class="col-2">
                                    {!! Form::Text('contents['.$key.'][quantity]', (isset($val['quantity'])) ? $val['quantity']: null, ['id' => 'contents-'.$key.'-quantity', 'class' => 'form-control form-control-sm numeric-only-required', 'maxlength' => 12]) !!}
                                </div>

                                <div class="col-2">
                                    <div class="input-group input-group-sm">
                                        {!! Form::Text('contents['.$key.'][unit_weight]', (isset($val['unit_weight'])) ? $val['unit_weight']: null, ['id' => 'contents-'.$key.'-unit-weight', 'class' => 'form-control decimal-only', 'maxlength' => 12]) !!}
                                        <div class="input-group-append">
                                            <span id="currency_code_1" class="input-group-text text-uppercase">KG</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-2">
                                    <div class="input-group input-group-sm">
                                        {!! Form::Text('contents['.$key.'][unit_value]', (isset($val['unit_value'])) ? $val['unit_value']: null, ['id' => 'contents-'.$key.'-unit-value', 'class' => 'form-control decimal-only', 'maxlength' => 12]) !!}
                                        <div class="input-group-append">
                                            <span id="currency_code_1" class="input-group-text text-uppercase">GBP</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-1 pt-2">
                                    <a href="#" title="Remove Commodity"><i class="fas fa-times fa-lg remove-commodity"></i></a>
                                </div>

                                {!! Form::hidden('contents['.$key.'][currency_code]', (isset($val['currency_code'])) ? $val['currency_code']: null, ['id' => 'contents-'.$key.'-currency-code']) !!}
                                {!! Form::hidden('contents['.$key.'][country_of_manufacture]', (isset($val['country_of_manufacture'])) ? $val['country_of_manufacture']: null, ['id' => 'contents-'.$key.'-country-of-manufacture']) !!}
                                {!! Form::hidden('contents['.$key.'][manufacturer]', (isset($val['manufacturer'])) ? $val['manufacturer']: null, ['id' => 'contents-'.$key.'-manufacturer']) !!}
                                {!! Form::hidden('contents['.$key.'][uom]', (isset($val['uom'])) ? $val['uom']: null, ['id' => 'contents-'.$key.'-uom']) !!}
                                {!! Form::hidden('contents['.$key.'][commodity_code]', (isset($val['commodity_code'])) ? $val['commodity_code']: null, ['id' => 'contents-'.$key.'-commodity-code']) !!}
                                {!! Form::hidden('contents['.$key.'][harmonized_code]', (isset($val['harmonized_code'])) ? $val['harmonized_code']: null, ['id' => 'contents-'.$key.'-harmonized-code']) !!}
                                {!! Form::hidden('contents['.$key.'][shipping_cost]', (isset($val['shipping_cost'])) ? $val['shipping_cost']: null, ['id' => 'contents-'.$key.'-shipping-cost']) !!}
                            </div>
                        @endforeach

                        @if(count($arrays['contents'])<= 0)
                            <div id="customs-information" class="well well-lg text-center mt-5">
                                Please describe the contents of your shipment. Click the
                                <strong>Add Commodity</strong> button to get started.
                            </div>
                        @endif

                    </div> <!-- /card-body pt-2 pb-2 text-nowrap -->
                </div><!-- /card -->


                <!-- ==================================================================================================================================================== -->
                <!-- ==================================================================================================================================================== -->

                <div id="panel-docs-only" class="card">
                    <div class="card-header font-weight-bold pt-1 pb-1">Documents Description</div>
                    <div class="card-body pt-2 pb-2 text-nowrap">
                        <div class="docs-only text-center pt-4 font-weight-bold">
                            <textarea name="documents_description" id="documents_description" rows="2" maxlength="40" class="form-control">{{ old('documents_description', 'BUSINESS DOCUMENTS ONLY') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- ==================================================================================================================================================== -->
                <!-- ==================================================================================================================================================== -->

                <div id="panel-goods-description" class="card">
                    <div class="card-header font-weight-bold pt-1 pb-1">Goods Description</div>
                    <div class="card-body pt-2 pb-2 text-nowrap">
                        <div class="goods-description text-center pt-4 font-weight-bold">
                            <textarea name="goods_description" id="goods_description" rows="2" maxlength="40" class="form-control">{{ old('goods_description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- ==================================================================================================================================================== -->
                <!-- ==================================================================================================================================================== -->

            </div> <!-- /col-md-8 -->

        </div> <!-- /row -->

    </div>

    <!-- Summary page -->

    <div id="summary">

        <button id="button-previous" class="btn btn-primary btn-lg btn-shadow float-right" type="button">
            <span class="fas fa-chevron-left" aria-hidden="true"></span> Previous Screen
        </button>

        <div class="clearfix"></div>

        <div class="row mb-2">

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header font-weight-bold pt-1 pb-1">
                        <span class="fas fa-user" aria-hidden="true"></span> Sender / Recipient
                    </div>
                    <div class="card-body text-nowrap text-large pt-3 pb-1">

                        <div class="row">

                            <div class="col-md-5">
                                <address id="summary-sender">
                                    <h5><u>Sender</u></h5>
                                    <span id="summary_sender_name"></span><br>
                                    <span id="summary_sender_company_name"></span><br>
                                    <span id="summary_sender_address1"></span><span id="summary_sender_address2"></span><br>
                                    <span id="summary_sender_city"></span>, <span id="summary_sender_state"></span>
                                    <span id="summary_sender_postcode"></span><br>
                                    <span class="font-weight-bold" id="summary_sender_country_code"></span><br><br>
                                    <span id="summary_sender_telephone"></span><br>
                                    <a id="summary_sender_email" href="mailto:#"></a>
                                </address>
                            </div>

                            <div class="col-sm-2 pt-4">
                                <span class="chevron fa fa-chevron-right pt-4 mt-4" aria-hidden="true"></span>
                            </div>

                            <div class="col-md-5">
                                <address id="summary-recipient">
                                    <h5><u>Recipient</u></h5>
                                    <span id="summary_recipient_name"></span><br>
                                    <span id="summary_recipient_company_name"></span><br>
                                    <span id="summary_recipient_address1"></span><span id="summary_recipient_address2"></span><br>
                                    <span id="summary_recipient_city"></span>,
                                    <span id="summary_recipient_state"></span>
                                    <span id="summary_recipient_postcode"></span><br>
                                    <span class="font-weight-bold" id="summary_recipient_country_code"></span><br><br>
                                    <span id="summary_recipient_telephone"></span><br>
                                    <a id="summary_recipient_email" href="mailto:#"></a>
                                </address>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">

                <div class="card h-100">
                    <div class="card-header font-weight-bold pt-1 pb-1">Overview</div>

                    <div class="card-body pt-2 pb-2 text-nowrap text-large">
                        <div class="row mb-2">
                            <div class="col-md-5">
                                <span class="fas fa-fw fa-paperclip mr-sm-3" aria-hidden="true"></span>
                                <strong>Ship Ref</strong></div>
                            <div id="summary_shipment_reference" class="col-md-7"></div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-5"><span class="fas fa-fw fa-calendar mr-sm-3" aria-hidden="true"></span>
                                <strong>Collection</strong></div>
                            <div id="summary_collection_date" class="col-md-7"></div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-5"><span class="fas fa-fw fa-random mr-sm-3" aria-hidden="true"></span>
                                <strong>Ship Reason</strong></div>
                            <div id="summary_ship_reason" class="col-md-7"></div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-5"><span class="fas fa-fw fa-fire mr-sm-3" aria-hidden="true"></span>
                                <strong>Hazardous</strong></div>
                            <div id="summary_hazardous" class="col-md-7"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-5"><span class="fas fa-fw fa-user mr-sm-3" aria-hidden="true"></span>
                                <strong>Bill To</strong></div>
                            <div class="col-md-7">
                                <span id="summary_bill_shipping" title="Bill Shipping To">Default</span> |
                                <span id="summary_bill_tax_duty" title="Bill Tax And Duty To">Default</span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-5">
                                <span class="fas fa-fw fa-credit-card mr-sm-3" aria-hidden="true"></span>
                                <strong>Bill Account</strong></div>
                            <div class="col-md-7">
                                <span id="summary_bill_shipping_account" title="Bill Shipping To Account">Default</span> |
                                <span id="summary_bill_tax_duty_account" title="Bill Tax And Duty To Account">Default</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5"><span class="fas fa-fw fa-list mr-sm-3" aria-hidden="true"></span>
                                <strong>Options</strong></div>
                            <div class="col-md-7">
                                <span id="no-options-selected">None selected</span>
                                <ul id="summary_options" class="list-inline">
                                    <li class="list-inline-item" id="summary_insured">
                                        <span class="fas fa-fw fa-check text-success" aria-hidden="true"></span> Insured
                                    </li>
                                    <li class="list-inline-item" id="summary_alcohol">
                                        <span class="fas fa-fw fa-check text-success" aria-hidden="true"></span> Alcohol
                                    </li>
                                    <li class="list-inline-item" id="summary_dry_ice">
                                        <span class="fas fa-fw fa-check text-success" aria-hidden="true"></span> Dry Ice
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-2">

            <div class="col-md-8">
                <div id="panel-select-service" class="card">
                    <div class="card-header font-weight-bold pt-1 pb-1">Select a Service</div>
                    <div id="select-service-body" class="card-body pt-2 pb-2 text-nowrap"></div>
                </div>
            </div>

            <div class="col-md-4">

                <div class="card">
                    <div class="card-header font-weight-bold pt-1 pb-1">Finalise</div>
                    <div class="card-body pt-2 pb-1 text-nowrap text-large">

                        <div class="row border-bottom pt-2 pb-2">
                            <div class="col-md-3">
                                <strong>Service:</strong>
                            </div>
                            <div class="col-md-9">
                                <div id="summary_service" class="float-right">Unavailable</div>
                            </div>
                        </div>

                        <div class="row border-bottom pt-2 pb-2">
                            <div class="col-md-6">
                                <strong>Pieces:</strong>
                            </div>
                            <div class="col-md-6">
                                <div id="summary_pieces" class="float-right"></div>
                            </div>
                        </div>

                        <div class="row border-bottom pt-2 pb-2">
                            <div class="col-md-6">
                                <strong>Value:</strong>
                                <span class="fas fa-info-circle" aria-hidden="true" data-placement="right" data-toggle="tooltip" data-original-title="Shipment value is calculated from the commodity values entered on the previous screen (quantity x unit value for each line)."></span>
                            </div>
                            <div class="col-md-6">
                                <div class="float-right">
                                    <span id="summary_customs_value">0.00</span>
                                    <span id="summary_customs_value_currency_code"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row border-bottom pt-2 pb-2">
                            <div class="col-md-6">
                                <strong>Weight:</strong>
                                <span class="fas fa-info-circle" aria-hidden="true" data-placement="right" data-toggle="tooltip" data-original-title="The total weight of your shipment - rounded up to the nearest half KG"></span>
                            </div>
                            <div class="col-md-6">
                                <div id="summary_weight" class="float-right">0.00</div>
                            </div>
                        </div>

                        <div class="row border-bottom pt-2 pb-2">
                            <div class="col-md-7">
                                <strong>Volumetric Weight:</strong>
                                <span class="fas fa-info-circle" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="Some useful info about vol weight here - rounded up to the nearest half KG"></span>
                            </div>
                            <div class="col-md-5">
                                <div id="summary_volumetric_weight" class="float-right">0.00</div>
                            </div>
                        </div>

                        <div class="row border-bottom pt-2 pb-2">
                            <div class="col-md-12">
                                <h3 class="m-0 p-0">Total: <span id="summary_total" class="text-primary ml-sm-1"></span>
                                    <span class="text-small text-muted ml-sm-1">exc. VAT</span></h3>
                            </div>
                        </div>

                        <div class="text-center text-muted font-italic pt-2">Errors and Omissions Excepted (E&AMP;OE)</div>

                    </div>
                </div>
            </div>
        </div>

        <div class="float-right">
            <button id="button-create" class="ml-md-4 btn btn-success text-white btn-lg btn-shadow" type="submit">
                <span class="fas fa-print" aria-hidden="true"></span> Create Label
            </button>
        </div>

    </div> <!-- /summary -->


    {!! Form::Close() !!}

    @if(!isset($formView))
        @include('partials.modals.address_book')
        @include('shipments.partials.commodities')
        @include('shipments.partials.cost_breakdown')
    @endif

@endsection
