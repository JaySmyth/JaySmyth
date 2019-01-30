@extends('layouts.mail')

@section('content')

<h1>Application Error</h1>

<p>{{$exception->getMessage()}}</p>

@endsection