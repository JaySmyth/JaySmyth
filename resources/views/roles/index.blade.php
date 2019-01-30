@extends('layouts.app')

@section('content')

<h2>Roles</h2>

<div class="table table-striped-responsive">
    <table class="table table-striped">        
        <thead>
            <tr>
                <th>Role</th> 
                <th>Description</th>         
                <th class="text-center">Primary Role</th> 
                <th class="text-center">IFS Only</th>                                                               
                <th class="text-center">Users</th>  
            </tr>
        </thead>        
        <tbody>
            @foreach($roles as $role)
            <tr>                
                <td>
                    @if($role->primary && $role->name != 'ifsa')
                    <a href="{{url('/roles/' . $role->id . '/permissions')}}" title="Set Permissions">{{$role->label}}</a>
                    @else
                    {{$role->label}}
                    @endif
                </td>   
                <td>{{$role->description}}</td>                 
                <td class="text-center">
                    @if($role->primary)
                    <span class="text-success">Yes</span>
                    @else
                    No
                    @endif
                </td>
                <td class="text-center">
                    @if($role->ifs_only)
                    Yes
                    @else
                    No
                    @endif
                </td>
                <td class="text-center">
                    <a href="{{url('/users?role=' . $role->id)}}" title="View role users">{{$role->users()->count()}}</a>
                </td>  
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


@endsection