@extends('layouts.app')

@section('content')

@include('partials.title', ['title' => 'Sales Invoice Runs', 'results'=> $invoiceRuns, 'create' => 'invoice_run'])

<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>            
            <th>User</th>
            <th>Date / Time</th>
            <th>Department</th>            
            <th class="text-center">Status</th>
            <th class="text-right">Shipments</th>
            <th class="text-right">Costs</th>
            <th class="text-right">Sales</th>            
            <th class="text-right">Difference</th> 
        </tr>
    </thead>
    <tbody>
        @foreach($invoiceRuns as $run)
        <tr>
            <td><a href="{{ url('/invoice-runs', $run->id) }}">{{$run->id}}</a></td>
            <td>{{$run->user->name}}</td>
            <td>
                @if($run->last_run)
                {{$run->last_run->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}
                @else
                <span class="text-muted">Unknown</span>
                @endif                
            </td>            
            @if(isset($run->department->name))
            <td>{{$run->department->name}}</td>
            @else
            <td class="text-muted">Not Specified</td>
            @endif    
            <td class="text-center">
                @if($run->status == 'Failed')
                <span class="text-danger">{{$run->status}}</span>
                @else
                <span class="text-success">{{$run->status}}</span>
                @endif
            </td>
            <td class="text-right">{{number_format($run->total_shipments)}}</td>
            <td class="text-right">{{number_format($run->total_costs, 2)}}</td>
            <td class="text-right">{{number_format($run->total_sales, 2)}}</td>
            <td class="text-right">{{$run->difference_formatted}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@include('partials.no_results', ['title' => 'Sales Invoice Runs', 'results'=> $invoiceRuns])
@include('partials.pagination', ['results'=> $invoiceRuns])

@endsection