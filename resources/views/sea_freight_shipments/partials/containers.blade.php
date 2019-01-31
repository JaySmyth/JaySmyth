@if($shipment->containers()->count() > 0)

<h4 class="mb-2">Containers <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{$shipment->containers->count()}}</span></h4>

<table class="table table-striped table-bordered mb-5">
    <thead>
        <tr>
            <th>#</th>
            <th>Size</th>
            <th>Container Number</th>
            <th>Seal Number</th>
            <th>Description of Goods</th>
            <th>No. Cartons</th>
            <th>Weight (KG)</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($shipment->containers as $container)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$container->size}}</td>                
            <td>{{$container->number}}</td>
            <td>
                @if($container->seal_number)
                {{$container->seal_number}}
                @else
                <strong><span class="text-danger" data-placement="bottom" data-toggle="tooltip" data-original-title="Seal number has not been specified for container">** NO SEAL NUMBER **</span></strong>
                @endif
            </td>
            <td>{{$container->goods_description}}</td>
            <td>{{$container->number_of_cartons}}</td>
            <td>{{$container->weight}}</td>           
            <td class="text-center"> 
                @if(Auth::user()->hasIfsRole())            
                <a href="{{ url('/sea-freight/' .  $shipment->id . '/edit-container/' . $container->id) }}" title="Edit Container"><span class="fas fa-edit" aria-hidden="true"></span></a> 
                @else

                @if(!$container->seal_number)
                <a href="{{ url('/sea-freight/' .  $shipment->id . '/edit-seal-number/' . $container->id) }}" title="Update Seal Number"><span class="fas fa-lock" aria-hidden="true"></span></a> 
                @else
                <span class="fas fa-lock faded" aria-hidden="true" title="Update Seal Number"></span>
                @endif

                @endif
            </td>          
        </tr>
        @endforeach
    </tbody>
</table>

@else

@include('partials.alert', ['class' => 'alert-warning', 'heading' => 'Containers', 'body' => 'No containers have been added to this shipment yet.'])

@endif