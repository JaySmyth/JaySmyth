@extends('layouts.app')

@section('navSearchPlaceholder', 'Purchase invoice search...')

@section('advanced_search_form')

    <div class="form-group row">
        <label class="col-sm-4  col-form-label">
            Invoice or Account Number:
        </label>
        <div class="col-sm-6">
            {!! Form::Text('filter', Input::get('filter'), ['class' => 'form-control', 'maxlength' => '25']) !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4  col-form-label">
            Carrier Consignment or SCS Job:
        </label>
        <div class="col-sm-6">
            {!! Form::Text('consignment', Input::get('consignment'), ['class' => 'form-control', 'maxlength' => '20']) !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4  col-form-label">
            Carrier:
        </label>
        <div class="col-sm-6">
            {!! Form::select('carrier',  dropDown('carriers', 'All Carriers'), Input::get('carrier'), array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4 col-form-label">
            Date From / To:
        </label>

        <div class="col-sm-3">
            <input type="text" name="date_from" value="{{Input::get('date_from')}}" class="form-control datepicker" maxlength="10" placeholder="Date From">
        </div>
        <div class="col-sm-3">
            <input type="text" name="date_to" value="{{Input::get('date_to')}}" class="form-control datepicker" maxlength="10" placeholder="Date To">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4  col-form-label">
            Invoice Type:
        </label>
        <div class="col-sm-6">
            {!! Form::select('type', array('' => 'All Invoice Types', 'F' => 'Freight Invoices', 'D' => 'Duty Invocies', 'O' => 'Other'), Input::get('type'), array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4  col-form-label">
            Duty Invoices:
        </label>
        <div class="col-sm-6">
            {!! Form::select('import_export', array('' => 'All Duty Invoices', 'E' => 'Export Duty', 'I' => 'Import Duty'), Input::get('import_export'), array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4  col-form-label">
            Status:
        </label>
        <div class="col-sm-6">
            {!! Form::select('status',  dropDown('invoiceStatuses', 'All Statuses'), Input::get('status'), array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4  col-form-label">
            Invoice Received:
        </label>
        <div class="col-sm-6">
            {!! Form::select('received',  dropDown('boolean', 'All'), Input::get('received'), array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4  col-form-label">
            Invoice Queried:
        </label>
        <div class="col-sm-6">
            {!! Form::select('queried',  dropDown('boolean', 'All'), Input::get('queried'), array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4  col-form-label">
            Costs Entered to SCS:
        </label>
        <div class="col-sm-6">
            {!! Form::select('costs',  dropDown('boolean', 'All'), Input::get('costs'), array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4  col-form-label">
            Copy Docs Requested:
        </label>
        <div class="col-sm-6">
            {!! Form::select('copy_docs',  dropDown('boolean', 'All'), Input::get('copy_docs'), array('class' => 'form-control')) !!}
        </div>
    </div>

@endsection

@include('partials.modals.advanced_search')

@section('content')

@can('purchase_invoice_admin')
    @section('toolbar')
        <a href="purchase-invoices/download?{{Request::getQueryString()}}" title="Download Results"><span class="fas fa-cloud-download-alt fa-lg" aria-hidden="true"></span></a>
        <a href="{{ url('/purchase-invoices/copy-docs-email') }}" title="Send Copy Docs Email"><span class="fas fa-envelope fa-lg ml-sm-3" aria-hidden="true"></span></a>
        <a href="{{ url('/purchase-invoices/export-invoices') }}" title="Export Invoices"><span class="fas fa-upload fa-lg ml-sm-3" aria-hidden="true"></span></a>
    @endsection
@endcan

@include('partials.title', ['title' => 'purchase invoices', 'results'=> $purchaseInvoices])

<table class="table table-striped">
    <thead>
    <tr>
        <th>Invoice</th>
        <th>Carrier</th>
        <th>Account</th>
        <th>Date</th>
        <th class="text-center">Type</th>
        <th class="text-center">I/E</th>
        <th class="text-right">Total Cost</th>
        <th class="text-center">Currency</th>
        <th class="text-center">Received</th>
        <th class="text-center">Queried</th>
        <th class="text-center">Costs</th>
        <th class="text-center">Copy Docs</th>
        <th class="text-center">Status</th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    @foreach($purchaseInvoices as $invoice)
        <tr>
            <td>
                <a href="{{ url('/purchase-invoices', $invoice->id) }}" class="invoice-number">{{$invoice->invoice_number}}</a>
            </td>
            <td>{{$invoice->carrier->name ?? ''}}</td>
            <td>{{$invoice->account_number}}</td>
            <td>{{$invoice->date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</td>
            <td class="text-center">
                <span class="badge {{($invoice->type == 'F') ? 'badge-primary' : 'badge-secondary'}}" aria-hidden="true" data-placement="right" data-toggle="tooltip" data-original-title="{{verboseInvoiceType($invoice->type)}} Invoice">{{$invoice->type}}</span>
            </td>
            <td class="text-center">
                <span aria-hidden="true" data-placement="right" data-toggle="tooltip" data-original-title="{{verboseImportExport($invoice->import_export)}}">{{$invoice->import_export ?? 'n/a'}}</span>
            </td>
            <td class="text-right">{{$invoice->total}}</td>
            <td class="text-center">{{$invoice->currency_code}}</td>
            <td class="text-center">
                @if($invoice->status != 2 && Auth::user()->hasPermission('set_purchase_invoice_flags'))

                    @if($invoice->received)
                        <a href="{{ url('/purchase-invoices/' . $invoice->id . '/receive') }}" title="Remove receipt" class="set-invoice-not-received"><span class="fas fa-check" aria-hidden="true"></span></a>
                    @else
                        <a href="{{ url('/purchase-invoices/' . $invoice->id . '/receive') }}" title="Set to received" class="set-invoice-received"><span class="fas fa-times" aria-hidden="true"></span></a>
                    @endif

                @else

                    @if($invoice->received)
                        <span class="fas fa-check text-success" aria-hidden="true" data-placement="right" data-toggle="tooltip" data-original-title="Received at {{$invoice->date_received->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}"></span>
                    @else
                        <span class="fas fa-times" aria-hidden="true"></span>
                    @endif

                @endif


            </td>
            <td class="text-center">

                @if($invoice->status != 2 && Auth::user()->hasPermission('set_purchase_invoice_flags'))

                    @if($invoice->queried)
                        <a href="{{ url('/purchase-invoices/' . $invoice->id . '/query') }}" title="Remove query flag" class="set-invoice-not-queried"><span class="fas fa-check" aria-hidden="true"></span></a>
                    @else
                        <a href="{{ url('/purchase-invoices/' . $invoice->id . '/query') }}" title="Set to queried" class="set-invoice-queried"><span class="fas fa-times" aria-hidden="true"></span></a>
                    @endif

                @else

                    @if($invoice->queried)
                        <span class="fas fa-check text-success" aria-hidden="true"></span>
                    @else
                        <span class="fas fa-times" aria-hidden="true"></span>
                    @endif

                @endif


            </td>

            <td class="text-center">
                @if($invoice->status != 2 && Auth::user()->hasPermission('set_purchase_invoice_flags'))

                    @if($invoice->costs)
                        <a href="{{ url('/purchase-invoices/' . $invoice->id . '/costs') }}" title="Remove costs entered"><span class="fas fa-check" aria-hidden="true"></span></a>
                    @else
                        <a href="{{ url('/purchase-invoices/' . $invoice->id . '/costs') }}" title="Set costs entered"><span class="fas fa-times" aria-hidden="true"></span></a>
                    @endif

                @else

                    @if($invoice->costs)
                        <span class="fas fa-check text-success" aria-hidden="true"></span>
                    @else
                        <span class="fas fa-times" aria-hidden="true"></span>
                    @endif

                @endif
            </td>

            <td class="text-center">
                @if(!$invoice->copy_docs_email_sent  && $invoice->status != 2 && Auth::user()->hasPermission('set_purchase_invoice_flags'))

                    @if($invoice->copy_docs)
                        <a href="{{ url('/purchase-invoices/' . $invoice->id . '/copy-docs') }}" title="Remove copy docs"><span class="fas fa-check" aria-hidden="true"></span></a>
                    @else
                        <a href="{{ url('/purchase-invoices/' . $invoice->id . '/copy-docs') }}" title="Set copy docs"><span class="fas fa-times" aria-hidden="true"></span></a>
                    @endif

                @else

                    @if($invoice->copy_docs)
                        <span class="fas fa-check text-success" aria-hidden="true"></span>
                    @else
                        <span class="fas fa-times" aria-hidden="true"></span>
                    @endif

                @endif
            </td>
            <td class="text-center">
                <span class="{{$invoice->status_class}}">{{$invoice->status_name}}</span>
            </td>
            <td class="text-center text-nowrap">

                <a href="{{ url('/purchase-invoices/' .  $invoice->id . '/detail') }}" title="Detail View"><span class="fas fa-th-list" aria-hidden="true"></span></a>

                <a href="{{ url('/purchase-invoices/' .  $invoice->id . '/compare') }}" title="Compare Costs"><span class="fas fa-calculator ml-sm-2" aria-hidden="true"></span></a>

            @can('purchase_invoice_admin')

                <!-- Only enable  -->
                    @if($invoice->status == 0)
                        <a href="{{ url('/purchase-invoices/' .  $invoice->id . '/pass') }}" title="Pass Invoice"><span class="far fa-check-square ml-sm-2" aria-hidden="true"></span></a>
                    @else
                        <span class="far fa-check-square ml-sm-2 faded" aria-hidden="true" title="Pass Invoice (unavailable)"></span>
                    @endif

                <!-- Only enable if not previously exported and invoice has been passed -->
                    @if(!$invoice->exported && $invoice->status == 1)
                        <a href="{{ url('/purchase-invoices/' .  $invoice->id . '/export') }}" title="Export"><span class="fas fa-upload ml-sm-2" aria-hidden="true"></span></a>
                    @else
                        <span class="fas fa-upload ml-sm-2 faded" aria-hidden="true" title="Export (unavailable)"></span>
                    @endif

                @endcan

            </td>

        </tr>
    @endforeach
    </tbody>
</table>


@include('partials.no_results', ['title' => 'purchase invoices', 'results'=> $purchaseInvoices])
@include('partials.pagination', ['results'=> $purchaseInvoices])

@endsection