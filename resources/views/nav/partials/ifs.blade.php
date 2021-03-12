@if(Auth::user()->canUploadShipments() && !Auth::user()->hasMultipleModes() && !Auth::user()->hasRole('cudv') && Auth::user()->getOnlyMode() != 'sea')

<li class="nav-item ml-sm-2">
    <a class="nav-link" href="{{url('shipments/upload')}}">Upload</a>
</li>

@endif
@can('view_manifest')
@if(Auth::user()->can('run_manifest'))

<li class="nav-item dropdown ml-sm-2">

    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Manifests</a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{url('/manifests')}}">Manifests</a>


        @can('run_manifest')
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{url('/manifest-profiles')}}">Run Manifest</a>
        @endcan
    </div>
</li>

@else

<li class="nav-item ml-sm-2">
    <a class="nav-link" href="{{url('/manifests')}}">Manifests</a>
</li>

@endif
@endcan

@if(Auth::user()->hasReports())
<li class="nav-item ml-sm-2">
    <a class="nav-link" href="{{url('/reports')}}">Reports</a>
</li>
@endif

@if(Auth::user()->hasRole('ifsc') && Auth::user()->can('view_customs_entry'))
<li class="nav-item ml-sm-2">
    <a class="nav-link" href="{{url('/customs-entries')}}" class="right-border"><span class="text-uppercase">Customs Entries</span></a>
</li>
@endif


@can('accounts_menu')

<li class="nav-item dropdown ml-sm-2">
    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Accounts</a>
    <div class="dropdown-menu">
        @can('view_purchase_invoice')<a class="dropdown-item" href="{{url('/purchase-invoices')}}">Purchase Invoices</a>@endcan
        @can('view_invoice_run')<a class="dropdown-item" href="{{url('/invoice-runs')}}">Sales Invoice Runs</a>@endcan
        @can('view_invoice_run')<a class="dropdown-item" href="{{url('/rates')}}">Master Rate Sheets</a>@endcan
        @can('view_surcharges')<a class="dropdown-item" href="{{url('/surcharges')}}">Surcharges</a>@endcan
        @can('currency_admin')<a class="dropdown-item" href="{{url('/currencies')}}">Currencies</a>@endcan
        @can('view_carrier_charge_codes')<a class="dropdown-item" href="{{url('/carrier-charge-codes')}}">Carrier Charge Codes</a>@endcan
        @can('view_fuel_surcharge')<a class="dropdown-item" href="{{url('/fuel-surcharges')}}">Fuel Surcharges</a>@endcan
        @can('create_fuel_surcharge')<a class="dropdown-item" href="{{url('/fuel-surcharges/upload')}}">Fuel Surcharges Upload</a>@endcan
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{url('/shipments/update-dims')}}">Update DIMs</a>
        <a class="dropdown-item" href="{{url('/dim-check')}}">DIM Check Upload</a>
    </div>
</li>

@endcan


@can('transport_menu')

<li class="nav-item dropdown ml-sm-2">
    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Transport</a>
    <div class="dropdown-menu">
        @can('view_transport_job')<a class="dropdown-item" href="{{url('/transport-jobs')}}">Transport Jobs</a>@endcan
        @can('close_transport_job')<a class="dropdown-item" href="{{url('/transport-jobs/close')}}">POD/Close Jobs</a>@endcan
        <div class="dropdown-divider"></div>
        @can('create_postcode')<a class="dropdown-item" href="{{url('/postcodes')}}">Postcodes</a>@endcan
        <a class="dropdown-item" href="{{url('/ifs-nd-postcodes')}}">Non Del Postcodes</a>
        @can('update_dims')<a class="dropdown-item" href="{{url('/shipments/update-dims')}}">Update Dims</a>@endcan
        @can('create_transport_job')<a class="dropdown-item" href="{{url('/transport-jobs/create')}}">Collection Request</a>@endcan
        @can('create_transport_job')<a class="dropdown-item" href="{{url('/transport-jobs/create?type=delivery')}}">Delivery Request</a>@endcan
    </div>
</li>

@endcan


@can('admin_menu')

<li class="nav-item dropdown ml-sm-2">
    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Admin</a>
    <div class="dropdown-menu">
        @can('view_company')<a class="dropdown-item" href="{{url('/companies')}}">Companies</a>@endcan
        @can('view_user')<a class="dropdown-item" href="{{url('/users')}}">Users</a>@endcan
        @can('manage_addresses')<a class="dropdown-item" href="{{url('/addresses?definition=sender')}}">Senders</a>@endcan
        @can('manage_addresses')<a class="dropdown-item" href="{{url('/addresses?definition=recipient')}}">Recipients</a>@endcan
        @can('manage_commodities')<a class="dropdown-item" href="{{url('/commodities')}}">Commodities</a>@endcan
        @can('view_customs_entry')<a class="dropdown-item" href="{{url('/customs-entries')}}" class="right-border">Customs Entries</a>@endcan
        @can('view_quotation')<a class="dropdown-item" href="{{url('/quotations')}}" class="right-border">Quotations</a>@endcan

        @if(Auth::user()->hasRole('ifsa') || Auth::user()->hasRole('ifsm'))
        <a class="dropdown-item" href="{{url('/shipments/status-upload')}}">Bulk Status Upload</a>
        @endif

        @can('create_user')
        <div class="dropdown-divider"></div>
        @endcan

        @can('create_company')<a class="dropdown-item" href="{{url('/companies/create')}}">New Company</a>@endcan
        @can('create_user')<a class="dropdown-item" href="{{url('/users/create')}}">New User</a>@endcan
    </div>
</li>

@endcan


@if(Auth::user()->getOnlyMode() == 'sea')

@can('view_quotation')
<li class="nav-item ml-sm-2">
    <a class="nav-link" href="{{url('quotations')}}">Quotations</a>
</li>
@endcan

@endif
