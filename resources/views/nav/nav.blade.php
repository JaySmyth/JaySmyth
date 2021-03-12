<nav class="navbar navbar-expand navbar-dark fixed-top">

    <div class="container-fluid">

        <a class="navbar-brand" href="{{url(Auth::user()->getDefaultRoute())}}">
            <img src="/images/ifs_logo_inverted.png" class="d-inline-block align-top" alt="IFS Global Logistics">
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">

            <ul class="navbar-nav mr-auto ml-sm-3">

                @can('create_shipment')

                    @if(Auth::user()->hasMultipleModes())

                        <li class="nav-item dropdown ml-sm-2">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                               aria-haspopup="true" aria-expanded="false">Ship</a>
                            <div class="dropdown-menu">
                                @can('courier')
                                    <a class="dropdown-item" href="{{url('/shipments/create')}}">Courier</a>@endcan
                                @can('sea')
                                    <a class="dropdown-item" href="{{url('/sea-freight/create')}}">Sea
                                        Freight</a>@endcan
                                @if(Auth::user()->canUploadShipments())
                                    <a class="dropdown-item" href="{{url('/shipments/upload')}}">Upload</a>@endif
                            </div>
                        </li>

                    @else

                        @can('courier')
                            <li class="nav-item ml-sm-2">
                                <a class="nav-link" href="{{url('/shipments/create')}}">Ship</a>
                            </li>
                        @endcan

                        @can('sea')
                            <li class="nav-item ml-sm-2">
                                <a class="nav-link" href="{{url('/sea-freight/create')}}">Ship</a>
                            </li>
                        @endcan

                    @endif

                    @if(Auth::user()->hasMultipleModes())

                        <li class="nav-item dropdown ml-sm-2">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                               aria-haspopup="true" aria-expanded="false">History</a>
                            <div class="dropdown-menu">
                                @can('courier')<a class="dropdown-item" href="{{url('/shipments')}}">Courier</a>@endcan
                                @can('sea')
                                    <a class="dropdown-item" href="{{url('/sea-freight')}}">Sea Freight</a>@endcan
                            </div>
                        </li>

                    @else

                        @can('courier')
                            <li class="nav-item ml-sm-2">
                                <a class="nav-link" href="{{url('/shipments')}}">History</a>
                            </li>
                        @endcan

                        @can('sea')
                            <li class="nav-item ml-sm-2">
                                <a class="nav-link" href="{{url('/sea-freight')}}">History</a>
                            </li>
                        @endcan

                    @endif

                @endcan

                @can('courier')
                    <li class="nav-item ml-sm-2">
                        @if(Request::is('track'))
                            <a class="nav-link" href="#">Track</a>
                        @else
                            <a class="nav-link" href="#" data-toggle="modal" data-target="#tracking_modal">Track</a>
                        @endif
                    </li>
                @endcan

                @if(Auth::user()->hasIfsRole())
                    @include('nav.partials.ifs')
                @else
                    @include('nav.partials.customer')

                    <li class="nav-item">
                        <button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#feedback_modal">Feedback</button>
                    </li>
                @endif
            </ul>

            <ul class="navbar-nav mr-sm-1">

                <li class="nav-item dropdown ml-sm-2">
                    <a class="nav-link dropdown-toggle text-nowrap" data-toggle="dropdown" href="#" role="button"
                       aria-haspopup="true" aria-expanded="false"><span class="fas fa-fw fa-user fa-lg text-white"
                                                                        aria-hidden="true"></span> {{Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{url('/account')}}"><span class="fas fa-fw fa-user mr-sm-2"
                                                                                  aria-hidden="true"></span> Account</a>
                        <a class="dropdown-item" href="{{url('/account/settings')}}"><span
                                    class="fas fa-fw fa-cog mr-sm-2" aria-hidden="true"></span> Settings</a>
                        <a class="dropdown-item" href="{{url('/account/password')}}"><span
                                    class="fas fa-fw fa-key mr-sm-2" aria-hidden="true"></span> Change Password</a>
                        @if(!Auth::user()->hasIfsRole())
                            <a class="dropdown-item" href="{{url('/feedback')}}"><i
                                        class="fas fa-comments fa-fw mr-sm-2"></i> Feedback</a>
                        @endif
                        <a class="dropdown-item" href="{{url('/help')}}"><span class="fas fa-fw fa-question mr-sm-2"
                                                                               aria-hidden="true"></span> Help</a>
                        <a class="dropdown-item" href="{{url('/logout')}}"><span
                                    class="fas fa-fw fa-sign-out-alt mr-sm-2" aria-hidden="true"></span> Logout</a>
                    </div>
                </li>

                @if(Auth::user()->hasRole('ifsa'))
                    <li class="nav-item dropdown ml-sm-2">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="true" aria-expanded="false"><i class="fas fa-cogs text-white"></i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="{{url('/error-logs')}}">Error Logs</a>
                            <a class="dropdown-item" href="{{url('/file-uploads')}}">File Uploads</a>
                            <a class="dropdown-item" href="{{url('/import-configs')}}">Import Configs</a>
                            <a class="dropdown-item" href="{{url('/jobs')}}">Job Queue</a>
                            <a class="dropdown-item" href="{{url('/logs')}}">Logs</a>
                            <a class="dropdown-item" href="{{url('/messages')}}">Messages</a>
                            <a class="dropdown-item" href="{{url('/service-messages')}}">Service Messages</a>
                            <a class="dropdown-item" href="{{url('/processes')}}">Process Monitor</a>
                            <a class="dropdown-item" href="{{url('/roles')}}">Roles</a>
                            <a class="dropdown-item" href="{{url('/shipment-uploads')}}">Shipment Uploads</a>
                        </div>
                    </li>
                @endif
            </ul>

            {!! Form::Open(['id' => 'nav-search', 'url' => Request::path(), 'method' => 'get', 'class' => 'form-inline mt-3 ml-sm-4', 'autocomplete' => 'off']) !!}
            <div class="input-group">
                <input type="text" name="filter" id="filter" value="{{Request::get('filter')}}" class="form-control"
                       placeholder="@yield('navSearchPlaceholder')" aria-label="Search">
                <span class="input-group-btn">
                    <button type="submit" class="btn"><span class="fas fa-fw fa-search fa-lg" aria-hidden="true"></span></button>
                </span>
            </div>

            <a href="#" class="advanced-search float-right text-nowrap" data-toggle="modal"
               data-target="#advanced_search">Advanced Search</a>

            {!! Form::Close() !!}

        </div>

    </div>
</nav>
