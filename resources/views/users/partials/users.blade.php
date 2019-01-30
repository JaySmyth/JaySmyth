
    <table class="table table-striped table-{{$class}} mb-5">        
        <thead>
            <tr>
                @if($iteration)<th>#</th>@endif
                <th>Name</th> 
                <th>Email</th>                
                <th>Role</th>                                                
                <th>Date Created</th>                
                <th>Last Login</th>
                <th class="text-center">Companies</th>
                <th class="text-center">Status</th> 
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>         
                @if($iteration)<td>{{$loop->iteration}}</td>@endif
                <td>@can('view_user')<a href="{{ url('/users', $user->id) }}">{{$user->name}}</a> @else {{$user->name}}@endcan</td>      
                <td><a href="mailto:{{$user->email}}">{{$user->email}}</a></td>               
                <td>{{$user->primary_role_label}}</td>                
                <td>{{$user->created_at->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</td>                               
                <td>{{$user->getLastLogin()}}</td>
                <td class="text-center">{{$user->companies->count()}}</td>
                <td class="text-center">@if($user->enabled)<span class="text-success">Enabled</span>@else<span class="text-danger">Disabled</span>@endif</td>                
                <td class="text-center text-nowrap">
                    @can('update_user')<a href="{{ url('/users/' . $user->id . '/edit') }}" title="Edit User"><span class="fas fa-edit" aria-hidden="true"></span></a>@endcan                  
                    @can('reset_password')<a href="{{ url('/users/' . $user->id . '/reset-password') }}" title="Reset Password"><span class="fas fa-key ml-sm-2" aria-hidden="true"></span></a>@endcan
                    @can('courier')<a href="{{url('/shipments?user=' . $user->id)}}" title="Shipment History"><span class="fas fa-history ml-sm-2" aria-hidden="true"></span></a>@endcan                                    
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>    
