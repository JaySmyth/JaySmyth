<div class="dropdown float-right">
    <button class="btn btn-outline-secondary btn-sm btn-xs dropdown-toggle ml-sm-3 btn-xs" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Actions
    </button>

    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

        @can('update_company')
        <a class="dropdown-item" href="{{ url('/companies/' . $company->id . '/edit') }}" title="Edit Company Record"><span class="fas fa-edit mr-sm-2" aria-hidden="true"></span> Edit Company</a>
        @endcan

        @can('change_company_status')
        <a class="dropdown-item" href="{{ url('/companies/' . $company->id . '/status') }}" title="Change Company Status"><span class="fas fa-ban mr-sm-2" aria-hidden="true"></span> Change Status</a>
        @endcan

        @can('set_company_services')
        <a class="dropdown-item" href="{{ url('/companies/' . $company->id . '/services') }}" title="Set Services"><span class="fas fa-tasks mr-sm-2" aria-hidden="true"></span> Set Services</a>
        @endcan

        @can('set_collection_settings')
        <a class="dropdown-item" href="{{ url('/companies/' . $company->id . '/collection-settings') }}" title="Collection Settings"><span class="fas fa-truck mr-sm-2" aria-hidden="true"></span> Collection Settings</a>
        @endcan

        @can('courier')
        <a class="dropdown-item" href="{{url('/shipments?company=' . $company->id)}}" title="View Shipping History"><span class="fas fa-history mr-sm-2" aria-hidden="true"></span> Shipment History</a>
        @endcan

    </div>
</div>