<h4 class="mb-2">

    Tracking Events <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{$shipment->tracking->count()}}</span>

    @if(stristr($shipment->carrier_tracking_url, 'dbschenker'))
    <small class="ml-sm-2"><a href="{{$shipment->carrier_tracking_url}}" target="_blank">View Airline Tracking</a></small>
    @endif

</h4>

{!! Form::Open(['url' => 'tracking', 'autocomplete' => 'off']) !!}

<table class="table table-striped table-bordered mb-5">
    <thead>
        <tr>
            <th>#</th>
            <th><span class="far fa-calendar-alt" aria-hidden="true"></span> Date</th>
            <th class="text-nowrap"><span class="far fa-clock" aria-hidden="true"></span> Local Time</th>
            <th><span class="fas fa-exchange-alt" aria-hidden="true"></span> Event</th>
            <th class="text-nowrap"><span class="fas fa-map-marker" aria-hidden="true"></span> Location</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($shipment->tracking as $tracking)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td class="text-nowrap">
                @if($tracking->local_datetime)
                {{$tracking->datetime->timezone(Auth::user()->time_zone)->format('jS M - Y')}}
                @endif
            </td>
            <td class="text-nowrap">
                @if($tracking->local_datetime)
                {{$tracking->datetime->timezone(Auth::user()->time_zone)->format('g:ia')}}
                @endif
            </td>
            <td>
                {{$tracking->message}}

                @if($tracking->user_id > 0 )
                - <em>{{$tracking->user_name}}</em>
                @endif

                @if(Auth::user()->hasIfsRole() && $tracking->user_id == Auth::user()->id && $tracking->status != 'cancelled')
                <div class="float-right"><a href="{{ url('/tracking/' . $tracking->id) }}" title="Delete Tracking Event" class="delete" data-record-name="tracking event"><i class="fas fa-times"></i></a></div>
                @endif

            </td>
            <td class="text-nowrap">
                @if($tracking->city || $tracking->country_code)
                {{$tracking->city}}

                @if($tracking->state)
                , {{$tracking->state}}
                @endif

                @if($tracking->country_code)
                - {{getCountry($tracking->country_code)}}
                @endif

                @else
                <i>n/a</i>
                @endif
            </td>
        </tr>
        @endforeach

    </tbody>
</table>

{!! Form::Close() !!}