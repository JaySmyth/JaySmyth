<h4 class="mb-2">Tracking Events
    <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{$shipment->tracking->count()}}</span></h4>

<table class="table table-striped table-bordered mb-5">
    <thead>
    <tr>
        <th>#</th>
        <th><span class="far fa-calendar-alt" aria-hidden="true"></span> Date</th>
        <th><span class="far fa-clock" aria-hidden="true"></span> Time</th>
        <th><span class="fas fa-flag" aria-hidden="true"></span> Status</th>
        <th><span class="fas fa-info-circle" aria-hidden="true"></span> Detail</th>
        @if(Auth::user()->hasIfsRole())
            <th class="text-center">&nbsp;</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach ($shipment->tracking as $tracking)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$tracking->datetime->timezone(Auth::user()->time_zone)->format('jS M - Y')}}</td>
            <td>{{$tracking->datetime->timezone(Auth::user()->time_zone)->format('g:ia')}}</td>
            <td>{{$tracking->status_name}}</td>
            <td>
                @if($tracking->status == 'delivered')
                    <strong>
                        <span class="far fa-check-circle fa-lg mr-sm-2 text-success" aria-hidden="true"></span>
                        {{$tracking->message}}
                    </strong>
                @else
                    {{$tracking->message}}
                @endif
            </td>
            @if(Auth::user()->hasIfsRole())
                <td class="text-center">
                    <a href="{{ url('/sea-freight-tracking/' .  $tracking->id . '/edit') }}" title="Edit Tracking"><span class="fas fa-edit" aria-hidden="true"></span></a>
                </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>