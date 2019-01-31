@extends('layouts.app')

@section('content')

<h2>Set Permissions - {{$role->label}} Role</h2>



{!! Form::model($role, ['method' => 'POST', 'url' => ['roles/permissions', $role->id], 'class' => '', 'autocomplete' => 'off']) !!}

@foreach ($permissions as $permission)
<div class="checkbox text-large">
    <label>
        {!! Form::checkbox('permissions[]', $permission->id) !!}
        {{$permission->label}}
    </label>
</div>
@endforeach

<div class="buttons-main text-center">
    <a class="back btn btn-secondary" role="button">Cancel</a>
    <button enabled="submit" class="btn btn-primary">Update Permissions</button>   
</div>

{!! Form::Close() !!}

@endsection