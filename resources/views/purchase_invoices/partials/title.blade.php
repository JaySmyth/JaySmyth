<h2>{{$purchaseInvoice->carrier->name}} Purchase Invoice - {{$purchaseInvoice->invoice_number}}
    
    @if(isset($small))
    <small class="text-muted ml-sm-4">{{$small}}</small>
    @endif

    <div class="float-right">        
        <span class="{{$purchaseInvoice->status_class}}">{{$purchaseInvoice->status_name}}</span>
    </div>

</h2>

<table class="table table-striped table-bordered mb-5">
    <thead>
        <tr class="active">
            <th>Date</th>
            <th>Account Number</th>
            <th>SCS Supplier Code</th>
            <th class="text-center">Type</th>
            <th class="text-center">Import / Export</th>
            <th class="text-center">Currency Code</th>
            <th class="text-right">Total Taxable</th>
            <th class="text-right">Total Non Taxable</th>
            <th class="text-right">Total</th>
            <th class="text-right">VAT</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{$purchaseInvoice->date->format('jS F, Y')}}</td>
            <td>{{$purchaseInvoice->account_number}}</td>
            <td>{{$purchaseInvoice->scs_supplier_code}}</td>
            <td class="text-center">{{verboseInvoiceType($purchaseInvoice->type)}}</td>
            <td class="text-center">{{verboseImportExport($purchaseInvoice->import_export)}}</td>
            <td class="text-center">{{$purchaseInvoice->currency_code}}</td>
            <td class="text-right">{{$purchaseInvoice->total_taxable}}</td>
            <td class="text-right">{{$purchaseInvoice->total_non_taxable}}</td>
            <td class="text-right">{{$purchaseInvoice->total}}</td>
            <td class="text-right">{{$purchaseInvoice->vat}}</td>
        </tr>
    </tbody>
</table>

@include('purchase_invoices.partials.actions', ['purchaseInvoice'=> $purchaseInvoice])