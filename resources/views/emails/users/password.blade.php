@extends('layouts.mail')

@section('content')

<p>Dear {{$user->name}},</p>

<p>Your password to access IFS Global Logistics has been reset.</p>

<p><b>Username:</b> {{$user->email}}<br><b>Password:</b> {{$password}}</p>

<p>If you have any problems accessing your account please contact the IT department at it@antrim.ifsgroup.com.</p>

@endsection