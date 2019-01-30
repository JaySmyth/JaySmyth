@if($logs->count() > 0 && Auth::user()->hasIfsRole())

<h4 class="mt-5 mb-2">Log <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{$logs->count()}}</span></h4>

<table id="log-table" class="table table-striped table-bordered mb-0">
    <thead>
        <tr>
            <th>#</th>
            <th><i class="far fa-calendar-alt mr-2" aria-hidden="true"></i> Date / Time</th>
            <th><i class="fas fa-info-circle mr-2"></i> Information</th>
            <th class="text-center"><i class="fas fa-table mr-2"></i> Data</th>
            <th><i class="far fa-comments mr-2"></i> Comments</th>
            <th><i class="fas fa-user mr-2" aria-hidden="true"></i> User</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($logs as $log)

        @if($loop->iteration > 10)
        <tr class="d-none">
            @else
        <tr>
            @endif

            <th scope="row">{{$loop->iteration}}</th>
            <td>{{$log->created_at->timezone(Auth::user()->time_zone)->format('d-m-Y H:i:s')}}</td>
            <td>
                {{$log->information}}
            </td>
            <td class="text-center">
                @if($log->data)
                <a href="{{ url('/logs/' . $log->id . '/get-data') }}" class="btn btn-outline-primary btn-sm ml-3 get-log-data" role="button">See data</a>
                @else
                <div class="text-center"><i class="fas fa-ellipsis-h faded"></i></div>
                @endif
            </td>
            <td>
                @if($log->comments)
                {{$log->comments}}
                @else
                <div class="text-center"><i class="fas fa-ellipsis-h faded"></i></div>
                @endif
            </td>
            <td>@can('view_user')<a href="{{ url('/users', $log->user->id) }}">{{$log->user->name}}</a> @else {{$log->user->name}}@endcan</td>
        </tr>
        @endforeach
    </tbody>
</table>

@if($logs->count() > 10)
<button type="button" class="btn btn-light btn-sm btn-block mb-5 rounded-0 see-more" data-see-more="#log-table"><i class="fas fa-chevron-circle-down"></i> See more</button>
@endif

<div class="mb-5"></div>

<!-- Modal -->
<div class="modal fade" id="log-data" tabindex="-1" role="dialog" aria-labelledby="logChanges" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h3 id="logChanges" class="mb-0"><i class="fas fa-table"></i> Data</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="log-data-body"></div>
        </div>
    </div>
</div>

@endif
