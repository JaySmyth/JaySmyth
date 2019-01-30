@can('purchase_invoice_admin')
<div class="dropdown float-right mb-2">
    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="{{ url('/purchase-invoices', $purchaseInvoice->id) }}" title="Overview"  role="button"><span class="fas fa-file-o mr-sm-2" aria-hidden="true"></span> Overview</a>          
        <a class="dropdown-item" href="{{ url('/purchase-invoices/' .  $purchaseInvoice->id . '/detail') }}" title="Compare Costs"  role="button"><span class="fas fa-th-list mr-sm-2" aria-hidden="true"></span> Detail View</a>          
        <a class="dropdown-item" href="{{ url('/purchase-invoices/' .  $purchaseInvoice->id . '/compare') }}" title="Compare Costs"  role="button"><span class="fas fa-calculator mr-sm-2" aria-hidden="true"></span> Compare Costs</a>          
        <div class="dropdown-divider"></div>        
        <a class="dropdown-item" href="{{ url('/purchase-invoices/' .  $purchaseInvoice->id . '/cost-comparison-download') }}" title="Download as Excel Document"><span class="far fa-file-excel mr-sm-2" aria-hidden="true"></span> Download Cost Comparison</a>        
        <a class="dropdown-item" href="{{ url('/purchase-invoices/' .  $purchaseInvoice->id . '/negative-variances-download') }}" title="Download as Excel Document"><span class="far fa-file-excel mr-sm-2" aria-hidden="true"></span> Download Negative Variances</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ url('/purchase-invoices/' .  $purchaseInvoice->id . '/negative-variances-email') }} " title="Send Negative Variances Email"  role="button"><span class="fas fa-envelope mr-sm-2" aria-hidden="true"></span> Email Negative Variances</a>        
        <div class="dropdown-divider"></div>
        @if(Auth::user()->hasRole('ifsa') || $purchaseInvoice->exported)
        <a class="dropdown-item" href="{{ url('/purchase-invoices/' .  $purchaseInvoice->id . '/download-xml') }}" title="Download Multifreight XML"  role="button"><span class="fas fa-file-code-o mr-sm-2" aria-hidden="true"></span> Download XML</a>
        @endif
        <a class="dropdown-item" href="{{ url('/purchase-invoices/' .  $purchaseInvoice->id . '/preview-xml') }}" title="Preview Multifreight XML"  role="button"><span class="fas fa-file-code-o mr-sm-2" aria-hidden="true"></span> Preview XML</a>
    </div>
</div>
@endcan