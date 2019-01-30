
@include('partials.title', ['title' => $surcharge->name, 'create' => 'surcharge'])
@if(!empty($surchargedetails))

<table class="table table-striped table-sm">        
    <thead>
        <tr>
            <th class="text-left">Description</th>
            <th class="text-right">Per Consignment</th>
            <th class="text-right">Per Kg</th> 
            <th class="text-right">Per Package</th>
            <th class="text-right">Min</th>                
            <th class="text-right">&nbsp</th>                
        </tr>
    </thead>

    <tbody>
        @foreach($surchargedetails as $charge)

        <tr>         
            <td class="text-left">{{$charge->name}}</td>
            <td class="text-right">{{$charge->consignment_rate}}</td>
            <td class="text-right">{{$charge->weight_rate}}</td>
            <td class="text-right">{{$charge->package_rate}}</td>
            <td class="text-right">{{$charge->min}}</td>
            <td class="text-center text-nowrap">
                @can('view_surcharges')<a href="{{ url('/surchargedetails/' . $surcharge->id . '/edit') }}" title="Edit Surcharge"><span class="fas fa-edit" aria-hidden="true"></span></a>@endcan
                @can('view_surcharges')<a href="{{ url('/surchargedetails/' . $surcharge->id . '/delete') }}" title="Delete Surcharge"><span class="fas fa-times ml-sm-2" aria-hidden="true"></span></a>@endcan
            </td>
        </tr>

        @endforeach
    </tbody>
</table>    

@else

<h4>No Surcharges Applicable</h4>

@endif
