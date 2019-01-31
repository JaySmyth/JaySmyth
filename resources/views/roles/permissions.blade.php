@extends('layouts.app')

@section('content')

<h2>Permissions - {{$role->label}} Role</h2>



<ul>
    @foreach ($permissions as $permission)
    <li class="margin-bottom-15"><h4>{{$permission->label}}</h4></li>
    @endforeach
</ul>

@endsection