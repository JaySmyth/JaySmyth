
@if($charges && !$charges->isEmpty())

        <h4>
            <br>
            @if(isset($rate->surcharge->name))
                Surcharge Model applied to Rate
            @else
                Unknown Model
            @endif
        </h4>
    <div>
        (Note: Not all charges will apply to every service)
    </div>
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-left">Description</th>
                <th class="text-right">Per Consignment</th>
                <th class="text-right">Per Kg</th>
                <th class="text-right">Per Package</th>
                <th class="text-right">Min</th>
                <th class="text-center">Company Specific</th>
            </tr>
        </thead>

        <tbody>
            @foreach($charges as $charge)

            <tr>
                <td class="text-left">{{$charge->name}}</td>
                <td class="text-right">
                    @if($charge->consignment_rate == "0.00")
                        -&nbsp;&nbsp;
                    @else
                        {{$charge->consignment_rate}}
                    @endif
                </td>
                <td class="text-right">
                    @if($charge->weight_rate == "0.00")
                        -&nbsp;&nbsp;
                    @else
                        {{$charge->weight_rate}}
                    @endif
                </td>
                <td class="text-right">
                    @if($charge->package_rate == "0.00")
                        -&nbsp;&nbsp;
                    @else
                        {{$charge->package_rate}}
                    @endif
                </td>
                <td class="text-right">
                    @if($charge->min == "0.00")
                        -&nbsp;&nbsp;
                    @else
                        {{$charge->min}}
                    @endif
                </td>

                @if($charge->company_id == 0)
                    <td class="text-center">No</td>
                @else
                <td class="text-center">Yes</td>
                @endif

            </tr>

            @endforeach
        </tbody>
    </table>

    @else

    <h4>No Surcharges Defined</h4>

@endif
