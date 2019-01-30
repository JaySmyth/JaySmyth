<div class="modal fade" id="cost_breakdown" tabindex="-1" role="dialog" aria-labelledby="cost_breakdown_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="cost_breakdown_title" class="modal-title text-capitalize">Price Quoted</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">                                
                <div class="modal-overflow">

                    @if(isset($quoted) && Auth::user()->hasIfsRole())
                    <h5>Costs <small>(Zone {{$quoted['costs_zone']}}) / {{$quoted['costs_packaging']}}</small></h5>
                    <table class="table table-sm">
                        <thead>
                            <tr class="table-warning">
                                <th>Charge</th>
                                <th>Description</th>
                                <th class="text-right">Amount</th>                                 
                            </tr>
                        </thead>
                        <tbody id="cost_breakdown_body">
                            @foreach($quoted['costs'] as $costs)
                            <tr>
                                <td>{{$costs['code']}}</td>
                                <td>{{$costs['description']}}</td>
                                <td class="text-right">{{$costs['value']}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-right text-large bg-light">
                                    {{$quoted['shipping_cost']}}

                                    @if(isset($quoted['sales_currency']))
                                    {{$quoted['sales_currency']}}
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @endif

                    @if(isset($quoted) && Auth::user()->hasIfsRole())
                    <h5>Sales <small>(Zone {{$quoted['sales_zone']}}) / {{$quoted['sales_packaging']}}</small></h5>
                    @endif

                    <table class="table table-sm">
                        <thead>
                            <tr class="table-primary">
                                <th>Charge</th>
                                <th>Description</th>
                                <th class="text-right">Amount</th>                                 
                            </tr>
                        </thead>
                        <tbody id="cost_breakdown_body">
                            @if(isset($quoted))
                            @foreach($quoted['sales'] as $sales)
                            <tr>
                                <td>{{$sales['code']}}</td>
                                <td>{{$sales['description']}}</td>
                                <td class="text-right">{{$sales['value']}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-right text-large bg-light">
                                    {{$quoted['shipping_charge']}}

                                    @if(isset($quoted['sales_currency']))
                                    {{$quoted['sales_currency']}}
                                    @endif
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    
                    <div class="text-center text-muted font-italic pt-2 text-small">Price quoted is based upon information provided at time of booking and may be subject to additional surcharges. Errors and Omissions Excepted (E&AMP;OE)</div>
                    
                </div>
            </div>
            <div class="modal-footer">        
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>