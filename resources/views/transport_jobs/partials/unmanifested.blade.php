<h4 class="mb-2">{{$title}} <small class="ml-sm-3">{{count($transportJobs)}} Towns/Cities</small></h4>

@foreach($transportJobs as $key => $groupedByCity)

<table class="table table-striped table-bordered table-sm mb-4">
    <thead>
        <tr class="bg-secondary text-white">
            <th width="11%">Reference</th>            
            <th width="4%" class="text-center">Service</th>
            @can('manifest_transport_jobs')<th width="2%" class="text-center"><input type="checkbox" class="check-all"></th>@endcan
            <th width="3%" class="text-center">Route</th>
            <th width="10%">Town / City</th>
            <th width="30%">Company</th>            
            <th width="6%">Postcode</th>
            <th width="4%" class="text-right">Pieces</th>
            <th width="11%" class="text-center">Date Specified</th>                         
            <th width="3%" class="text-center">Type</th>            
        </tr>
    </thead>
    <tbody>
        @foreach($groupedByCity as $transportJob)
        
            @if(!$transportJob->shipment)
                <tr class="text-uppercase bg-warning">
            @else
                <tr class="text-uppercase">
            @endif
        
            <td>
                <a href="{{url('/transport-jobs', $transportJob->id)}}">
                    @if($transportJob->shipment)
                    {{$transportJob->shipment->carrier_consignment_number}}
                    @elseif($transportJob->scs_job_number)
                    {{$transportJob->scs_job_number}}
                    @else
                    {{$transportJob->reference}}
                    @endif
                </a>                
            </td>
            <td class="text-center">
                @if($transportJob->shipment)
                <span class="text-uppercase badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$transportJob->shipment->service->name ?? 'Unknown'}}">{{$transportJob->shipment->service->code ?? ''}}</span>
                @else
                n/a
                @endif
            </td>
            @can('manifest_transport_jobs')
            <td class="text-center">
                {!! Form::checkbox('jobs['.$transportJob->id.']', 1, old('jobs['.$transportJob->id.']')) !!}
            </td>
            @endcan
            <td class="text-center">
                {{$transportJob->transend_route}}
            </td>
            <td>
                @if($transportJob->type == 'c')
                {{$transportJob->from_city}}
                @else
                {{$transportJob->to_city}}
                @endif
            </td>
            <td>
                @if($transportJob->type == 'c')
                {{$transportJob->from_company_name ?: $transportJob->from_name}}
                @else
                {{$transportJob->to_company_name ?: $transportJob->to_name}}
                @endif                
            </td>     
            <td>
                @if($transportJob->type == 'c')
                {{$transportJob->from_postcode}}
                @else
                {{$transportJob->to_postcode}}
                @endif
            </td>
            <td class="text-right">{{$transportJob->pieces}}</td>
            <td class="text-center">   
                @if(!$transportJob->date_requested->isToday())
                <span class="text-danger">{{$transportJob->date_requested->timezone(Auth::user()->time_zone)->format('l jS F')}}</span>
                @else
                {{$transportJob->date_requested->timezone(Auth::user()->time_zone)->format('l jS F')}}         
                @endif
            </td>      
            <td class="text-center">
                <span class="badge {{($transportJob->type == 'c') ? 'badge-secondary' : 'badge-primary'}}" data-placement="bottom" data-toggle="tooltip" data-original-title="{{($transportJob->type == 'c') ? 'Collection' : 'Delivery'}}">{{$transportJob->type}}</span>
            </td>  
        </tr>

        @endforeach

    </tbody>        
</table>

@endforeach


