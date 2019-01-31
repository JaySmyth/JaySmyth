@extends('layouts.mail')

@section('content')

@if($warning)
    <h1 class="error">{{$warning}}</h1>
@endif

@if($detail)
    <h2>{{$detail}}</h2>
@endif


@if(is_array($msg))

@foreach($msg as $m)

<p>{{$m}}</p>

@endforeach

@else
<h2>{{$msg}}</h2>
@endif

@if($path)

<h3>See attached file</h3>

@endif

@endsection