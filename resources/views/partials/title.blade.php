<div class="clearfix text-nowrap">

    <h2 class="float-left text-capitalize">{{$title}} @if(isset($badge))<span class="badge badge-secondary badge-sm">{{$badge}}</span>@endif @if(isset($small))<small>{{$small}}</small>@endif</h2>

    <div class="float-right mt-3">

        <div class="float-left mr-sm-3">

            @yield('toolbar')

            @if(isset($download) && $results->total() > 0)
            @can('download_' . $download)<a href="{{Request::url()}}/download?{{Request::getQueryString()}}" title="Download Results"><span class="fas fa-cloud-download-alt fa-lg" aria-hidden="true"></span></a>@endcan
            @endif

            @if(isset($create))
            @can('create_' . $create)
            @if(isset($url))
                <a href="{{url($url)}}/create"
            @else
                <a href="{{Request::url()}}/create"
            @endif
             class="btn btn-success btn-sm btn-xs ml-sm-3 pr-sm-2 text-white" title="New {{snakeCaseToWords($create)}}" role="button"> <span class="fas fa-plus-circle text-white mr-sm-1" aria-hidden="true"></span> New</a>@endcan
            @endif

        </div>

        @if(isset($results) && $results->total() > 0)
        <div class="float-right">
            <strong>{{$results->firstItem()}}</strong> - <strong>{{$results->lastItem()}}</strong> of <strong>{{number_format($results->total())}}</strong>
        </div>
        @endif

    </div>

</div>
