@extends('layouts.mail')

@section('content')

<h1 class="mb-3">{{$results['subject']}}</h1>

@if(count($results['failed']) > 0)
<h2>Failed records are detailed with in the <u>attached "failed.csv"</u> and also highlighted below. Please use the attached <span class="error">"failed.csv"</span> for your corrected upload.</h2>
@endif

<p><i>Upload performed by {{$results['user']['name']}}</i>.</p>

<table border="0" cellspacing="0" width="100%" class="table">

    <thead>
        <tr>
            <th>#</th>
            <th>Consignment Number</th>
            <th>Status changed to</th>
            <th>Result</th>

            @if(count($results['failed']) > 0)
            <th width="20%">Errors</th>
            @endif
        </tr>
    </thead>

    @if(count($results['rows']) > 0)
    @foreach($results['rows'] as $key => $result)

        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$result['data']['consignment_number']}}</td>
            <td>
                @if(isset($results['failed'][$key]['errors']))
                    - unchanged - 
                @else
                    {{$results['success'][$key]['status_code']}}
                @endif
            </td>
            <td>
                @if(isset($results['failed'][$key]['errors']))
                <span class="error">Failed</span>
                @else
                <span class="inserted">Updated</span>
                @endif
            </td>

            @if(count($results['failed']) > 0)
            <td class="error-summary">
                @if(isset($results['failed'][$key]['errors']))
                @foreach($results['failed'][$key]['errors'] as $error)

                        @if(is_array($error))
                            {{ implode(", ", $error) }}<br>
                        @else
                            * {{ ucfirst($error) }}<br>
                        @endif

                @endforeach
                @endif
            </td>
            @endif
        </tr>

    @endforeach
    @endif

</table>

@endsection
