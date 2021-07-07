@extends('layouts.mail')

@section('content')

<h1>Rate Increase Logs</h1>

<table border="0" cellspacing="0" width="100%" class="table">

    <thead>
        <tr>
            <th>#</th>
            <th>Log</th>
        </tr>
    </thead>

    @foreach($results as $result)
    <tr>
        @if(isset($result['data']))
        <td>{{$loop->iteration}}</td>
        <td>{{$result['data']['name']}}</td>
        @endif
    </tr>
    @endforeach

</table>

@endsection
