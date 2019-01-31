@if($customsEntry->customsEntryCommodity()->count() > 0)

<h4 class="mb-2">Commodities <span class="badge badge-pill badge-secondary">{{$customsEntry->customsEntryCommodity->count()}}</span></h4>

<table class="table table-striped table-bordered mb-5">
    <thead>
        <tr>
            <th>#</th>
            <th>Vendor</th>
            <th>Commodity Code</th>
            <th>Country Of Origin</th>
            <th class="text-right">Nett Weight (KG)</th>
            <th class="text-center">CPC</th>
            <th class="text-right">Duty Percent</th>
            <th class="text-right">Value (GBP)</th>
            <th class="text-right">Duty (GBP)</th>
            <th class="text-right">VAT (GBP)</th>
            @can('create_customs_entry')<th>&nbsp;</th>@endcan
        </tr>
    </thead>
    <tbody>
        @foreach ($customsEntry->customsEntryCommodity as $commodity)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$commodity->vendor}}</td>
            <td>{{$commodity->commodity_code}}</td>
            <td>
                @if($commodity->country_of_origin)
                {{getCountry($commodity->country_of_origin)}}
                @else
                Not specified
                @endif
            </td>
            <td class="text-right">{{$commodity->weight}}</td>
            <td class="text-center"><span title="{{$commodity->customsProcedureCode->description}}">{{$commodity->customsProcedureCode->code}}</span></td>
            <td class="text-right">{{$commodity->duty_percent}}</td>
            <td class="text-right">{{$commodity->value}}</td>
            <td class="text-right">{{$commodity->duty}}</td>
            <td class="text-right">{{$commodity->vat}}</td>
            @can('create_customs_entry')
            <td class="text-center">
                <a href="{{ url('/customs-entry-commodity/' . $commodity->id) }}" title="Delete Commodity" class="mr-2 delete" data-record-name="commodity"><i class="fas fa-times"></i></a>
                <a href="{{ url('/customs-entry-commodity/' .  $commodity->id . '/edit') }}" title="Edit Commodity"><span class="fas fa-edit" aria-hidden="true"></span></a>
            </td>
            @endcan
        </tr>
        @endforeach
    </tbody>
</table>

@endif