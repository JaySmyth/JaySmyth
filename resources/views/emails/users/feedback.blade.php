@extends('layouts.mail')

@section('content')

<h2>{{$user->name}} - {{$user->companies->first()->company_name}}</h2>

<ol>
    <li>Did we meet your delivery expectations? <strong>{{$answers['question_1']}}</strong></li>
    <li>Our communication with you met your needs? <strong>{{$answers['question_2']}}</strong></li>
    <li>Did you receive value for money? <strong>{{$answers['question_3']}}</strong></li>
    <li>How did you find our office staff? <strong>{{$answers['question_4']}}</strong></li>
    <li>How did you find our driver? <strong>{{$answers['question_5']}}</strong></li>
    <li>Would you be interested in hearing more about our courier, air, sea or road transport services? <strong>{{$answers['question_6']}}</strong></li>
    <li>Would you be interested in hearing more about our warehousing services? <strong>{{$answers['question_7']}}</strong></li>
    <li>You would use us again? <strong>{{$answers['question_8']}}</strong></li>
    <li>Would you recommend us to another company? <strong>{{$answers['question_9']}}</strong></li>
    <li>Our overall service was better than others you have tried? <strong>{{$answers['question_10']}}</strong></li>
</ol>

@endsection
