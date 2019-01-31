@extends('layouts.mail')

@section('content')

<h1>Job Failed - {{$name}}</h1>

@if($path)

<h2>See attached file</h2>

@endif

<pre>{{$exception}}</pre>

@endsection