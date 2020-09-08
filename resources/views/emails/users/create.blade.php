@extends('layouts.mail')

@section('content')

Ref: U/{{ Auth::User()->id }}

<p>Dear {{$user->name}},</p>

<p>Your account to access IFS Global Logistics has been created.</p>

<p><b>Username:</b> {{$user->email}}<br><b>Password:</b> {{$password}}</p>

<p>You can log into your new account at https://ship.ifsgroup.com</p>

<p>If you have any problems accessing your account please contact the IT department at it@antrim.ifsgroup.com.</p>

@endsection
