<div class="dropdown float-right">
    <button class="btn btn-outline-secondary btn-sm btn-xs dropdown-toggle ml-sm-3 btn-xs" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Actions
    </button>

    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
        @if($shipment->status->code == 'pre_transit' || Auth::user()->hasIfsRole() && $shipment->status->code != 'saved')
        <a class="dropdown-item" href="{{$shipment->print_url}}" title="Print Label"><span class="fas fa-print mr-sm-2" aria-hidden="true"></span> Print Label</a>
        @else
        <a href="#" class="dropdown-item disabled faded" title="Print Label (unavailable)"><span class="fas fa-print text-secondary mr-sm-2" aria-hidden="true"></span> Print Label</a>
        @endif

        @if($shipment->formViewAvailable())
        <a class="dropdown-item" href="{{ url('/shipments/' . $shipment->id . '/form-view') }}" title="Form View"><span class="fas fa-window-restore mr-sm-2" aria-hidden="true"></span> Form View</a>
        @else
        <a href="#" class="dropdown-item disabled faded" title="Form View (unavailable)"><span class="fas fa-window-restore text-secondary mr-sm-2" aria-hidden="true"></span> Form View</a>
        @endif


        @if($shipment->hasCommercialInvoice())
        <a class="dropdown-item" href="{{ url('/commercial-invoice', $shipment->token) }}" title="Commercial Invoice"><span class="fas fa-list-alt mr-sm-2" aria-hidden="true"></span> Commercial Invoice</a>
        <a class="dropdown-item" href="{{ url('/commercial-invoice/' . $shipment->token . '?type=p') }}" title="Proforma Invoice"><span class="fas fa-list-alt mr-sm-2" aria-hidden="true"></span> Proforma Invoice</a>
        @else
        <a href="#" class="dropdown-item disabled faded" title="Commercial Invoice (unavailable)"><span class="fas fa-list-alt mr-sm-2" aria-hidden="true"></span> Commercial Invoice</a>
        @endif

        <a class="dropdown-item" href="{{ url('/despatch-note', $shipment->token) }}" title="Despatch Note"><span class="fas fa-file mr-sm-2" aria-hidden="true"></span> Despatch Note</a>

        @if($shipment->isActive() && !$shipment->legacy)
        <a class="dropdown-item" href="{{ url('/documents/create/shipment/' . $shipment->id) }}" title="Upload Supporting Documents"><span class="fas fa-file mr-sm-2" aria-hidden="true"></span> Upload Documents</a>
        @else
        <a href="#" class="dropdown-item disabled faded" title="Upload Supporting Documents (unavailable)"><span class="fas fa-file text-secondary mr-sm-2" aria-hidden="true"></span> Upload Documents</a>
        @endif

        @if($shipment->status->name != 'saved' && $shipment->quoted)
        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#cost_breakdown" title="View Price Breakdown" role="button"><span class="fas fa-credit-card mr-sm-2" aria-hidden="true"></span> View Price Quoted</a>
        @else
        <a href="#" class="dropdown-item disabled faded" title="Price Quoted (unavailable)" role="button"><span class="fas fa-credit-card mr-sm-2" aria-hidden="true"></span> View Price Quoted</a>
        @endif

        @can('sales_invoice_admin')
        @if($shipment->status->name != 'saved' && !$shipment->legacy_pricing && !$shipment->invoicing_status)
        <a class="dropdown-item" href="{{ url('/shipments/' . $shipment->id . '/price') }}" title="Price/ Reprice shipment" role="button"><span class="fas fa-credit-card mr-sm-2" aria-hidden="true"></span> Price/ Reprice</a>
        @else
        <a href="#" class="dropdown-item disabled faded" title="Reprice (unavailable)" role="button"><span class="fas fa-credit-card mr-sm-2" aria-hidden="true"></span> Price/ Reprice</a>
        @endif
        @endcan

        <div class="dropdown-divider"></div>

        <a class="dropdown-item" href="{{ url('/tracking/' . $shipment->token) }}" title="Public Tracking Link" target="_blank"><i class="fas fa-external-link-alt mr-sm-2"></i> Public Tracking</a>

        @if(Auth::user()->hasIfsRole())

        @if($shipment->status->name != 'cancelled' && !$shipment->legacy)
        <a class="dropdown-item" href="{{ url('/tracking/' . $shipment->id . '/create') }}" title="Add Tracking Event" role="button"><span class="fas fa-random mr-sm-2" aria-hidden="true"></span> Add Tracking Event</a>
        @else
        <a href="#" class="dropdown-item disabled faded" title="Add Tracking Event (unavailable)" role="button"><span class="fas fa-random mr-sm-2" aria-hidden="true"></span> Add Tracking Event</a>
        @endif

        @if($shipment->carrier_tracking_url)
        <a class="dropdown-item" href="{{$shipment->carrier_tracking_url}}" title="{{$shipment->carrier_tracking_url}}" target="_blank" role="button"><span class="fas fa-plane mr-sm-2" aria-hidden="true"></span> Carrier's Tracking</a>
        @else
        <a href="#" class="dropdown-item disabled faded" title="View Carrier's Tracking (unavailable)" role="button"><span class="fas fa-plane mr-sm-2" aria-hidden="true"></span> Carrier's Tracking</a>
        @endif

        @can('view_purchase_invoice')
        @if($shipment->received && $shipment->carrier_id != 1)
        <a class="dropdown-item" href="{{ url('/purchase-invoices?filter=&consignment=' . $shipment->carrier_consignment_number . '&carrier=' . $shipment->carrier_id) }}" title="Search Purchase Invoices" role="button"><span class="fas fa-file mr-sm-2" aria-hidden="true"></span> Purchase Invoices</a>
        @else
        <a href="#" class="dropdown-item disabled faded" title="Search Purchase Invoices" role="button"><span class="fas fa-file mr-sm-2" aria-hidden="true"></span> Purchase Invoices</a>
        @endif
        @endcan

        @if(Auth::user()->hasRole('ifsa'))
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ url('/shipments/' . $shipment->id . '/transaction-log') }}" title="View Transaction Log" target="_blank" role="button"><span class="far fa-file-alt mr-sm-2" aria-hidden="true"></span> Transaction Log</a>        
        <a class="dropdown-item" href="{{ url('/shipments/' . $shipment->id . '/send-test-email') }}" title="Send Test Email" role="button"><span class="fas fa-envelope mr-sm-2" aria-hidden="true"></span> Send Test Email</a>
        @endif

        @endif

        <div class="dropdown-divider"></div>

        @if(Auth::user()->hasIfsRole())

        @if($shipment->status->code == 'received' && !$shipment->on_hold)
        <a class="dropdown-item hold-shipment" href="{{ url('/shipments/' . $shipment->id . '/hold') }}" title="Hold Shipment"><span class="fas fa-stop-circle mr-sm-2" aria-hidden="true"></span> Hold Shipment</a>
        @elseif($shipment->status->code == 'received' && $shipment->on_hold)
        <a class="dropdown-item hold-shipment" href="{{ url('/shipments/' . $shipment->id . '/hold') }}" title="Release Shipment"><span class="far fa-check-circle mr-sm-2" aria-hidden="true"></span> Release Shipment</a>
        @else
        <a href="#" class="dropdown-item disabled faded" title="Hold Shipment (unavailable)"  role="button"><span class="fas fa-stop-circle mr-sm-2" aria-hidden="true"></span> Hold Shipment</a>
        @endif

        @if(!$shipment->received)
        <a class="dropdown-item set-received" href="{{ url('/shipments/' . $shipment->id . '/receive') }}" title="Set to Received"><span class="fas fa-barcode mr-sm-2" aria-hidden="true"></span> Web Scan</a>
        @else
        <a href="#" class="dropdown-item disabled faded" title="Set to Received (unavailable)" role="button"><span class="fas fa-barcode mr-sm-2" aria-hidden="true"></span> Web Scan</a>
        @endif

        @endif

        @if($shipment->isCancellable() || Auth::user()->hasIfsRole())
        <a class="dropdown-item cancel-shipment" href="{{ url('/shipments/' . $shipment->id . '/cancel') }}" title="Cancel Shipment"><span class="fas fa-times mr-sm-2" aria-hidden="true"></span> Cancel Shipment</a>
        @else
        <a href="#" class="dropdown-item disabled faded" title="Cancel Shipment (unavailable)"><span class="fas fa-times mr-sm-2" aria-hidden="true"></span> Cancel Shipment</a>
        @endif

        @if(Auth::user()->hasIfsRole())
        
        @if($shipment->status->code == 'cancelled')
        <a class="dropdown-item" href="{{ url('/shipments/' . $shipment->id . '/undo-cancel') }}" title="Undo Cancel"><span class="fas fa-undo mr-sm-2" aria-hidden="true"></span> Undo Cancel</a>
        @else
        <a href="#" class="dropdown-item disabled faded" title="Undo Cancel (unavailable)"><span class="fas fa-undo mr-sm-2" aria-hidden="true"></span> Undo Cancel</a>
        @endif
        
        @endif


    </div>
</div>