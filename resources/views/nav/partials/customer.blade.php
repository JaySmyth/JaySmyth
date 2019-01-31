@if(Auth::user()->canUploadShipments() && !Auth::user()->hasMultipleModes() && !Auth::user()->hasRole('cudv') && Auth::user()->getOnlyMode() != 'sea')

<li class="nav-item ml-sm-2">
    <a class="nav-link" href="{{url('shipments/upload')}}">Upload</a>
</li>

@endif

@if(!Auth::user()->hasRole('cudv') && Auth::user()->getOnlyMode() != 'sea')

<li class="nav-item ml-sm-2">
    <a class="nav-link" href="{{url('reports')}}">Reports</a>
</li>

@if(Auth::user()->hasRole('cusa'))
<li class="nav-item dropdown ml-sm-2">
    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Admin</a>
    <div class="dropdown-menu">
        @can('view_user')<a class="dropdown-item" href="{{url('/users')}}"><span class="fas fa-fw fa-user-plus mr-sm-2" aria-hidden="true"></span> Users</a>@endcan
        @can('courier')
        @can('manage_addresses')<a class="dropdown-item" href="{{url('/addresses?definition=sender')}}"><span class="far fa-address-book fa-fw mr-sm-2" aria-hidden="true"></span> Senders</a>@endcan
        @can('manage_addresses')<a class="dropdown-item" href="{{url('/addresses?definition=recipient')}}"><span class="far fa-address-book fa-fw mr-sm-2" aria-hidden="true"></span> Recipients</a>@endcan
        @can('manage_commodities')<a class="dropdown-item" href="{{url('/commodities')}}"><span class="fas fa-fw fa-archive mr-sm-2" aria-hidden="true"></span> Commodities</a>@endcan
        @endcan
    </div>
</li>
@endif

@endif

@can('view_customs_entry')

<li class="nav-item ml-sm-2">
    <a class="nav-link" href="{{url('/customs-entries')}}">Customs Entries</a>
</li>

@endcan
