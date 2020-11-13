@extends('layouts.mail')

@section('content')

    <h3>Feedback from {{$user->name}} - {{$user->companies->first()->company_name}}</h3>

    <h4>How did we do today?</h4>

    @if($smiley == 'frown')
        <h5>POOR</h5>
    @elseif($smiley == 'meh')
        <h5>AVERAGE</h5>
    @else
        <h5>EXCELLENT</h5>
    @endif

    <p>{{ $comments }}</p>

@endsection
