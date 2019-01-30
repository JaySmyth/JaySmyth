<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\Permission;
use App\Mode;
use App\User;

class RolesController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Role Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles
      |
      |
      |
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 
     *
     * @param  
     * @return 
     */
    public function index()
    {
        $this->authorize(new Role());

        $roles = Role::orderBy('primary', 'DESC')->orderBy('label')->get();

        return view('roles.index', compact('roles'));
    }

    /**
     * 
     *
     * @param  
     * @return 
     */
    public function permissions(Request $request, $id)
    {
        $this->authorize(new Role());

        $role = Role::whereId($id)->where('name', '!=', 'ifsa')->wherePrimary(1)->firstOrFail();

        // Get an array of shipping modes
        $modes = Mode::all()->pluck('name');

        // Exclude the shipping mode permissions (uncomment once roles configured)
        $permissions = Permission::whereNotIn('name', $modes)->get();

        if ($request->user()->hasRole('ifsa')) {
            $view = 'roles.set_permissions';
        } else {
            $view = 'roles.permissions';
        }

        return view($view, compact('role', 'permissions'));
    }

    /**
     * 
     *
     * @param  
     * @return 
     */
    public function setPermissions(Request $request, $id)
    {
        $this->authorize(new Role());

        $role = Role::whereId($id)->where('name', '!=', 'ifsa')->wherePrimary(1)->firstOrFail();

        if ($request->permissions) {
            $role->permissions()->sync($request->permissions);
        } else {
            $role->permissions()->detach();
        }

        flash()->success('Permissions Set!', 'Role has been updated.');
        return redirect('roles');
    }

    /**
     * 
     *
     * @param  
     * @return 
     */
    public function getRoles(Request $request)
    {
        $this->authorize(new Role());

        if ($request->ajax()) {

            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                return 'error';
            }

            if (stristr($request->email, 'ifsgroup.com')) {
                $ifsOnly = true;
            }

            return Role::wherePrimary(1)->whereIfsOnly(isset($ifsOnly) ? 1 : 0)->orderBy('label')->pluck('label', 'id');
        }
    }

}
