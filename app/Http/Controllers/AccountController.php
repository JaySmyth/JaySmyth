<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => [
                'ip',
            ],
        ]);
    }

    /**
     * Display the authenticated user's account.
     *
     * @param
     * @return
     */
    public function show()
    {
        return view('account.show')->with('user', Auth::user());
    }

    /**
     * Displays update settings form the for authenticated user.
     *
     * @param
     * @return
     */
    public function edit()
    {
        return view('account.edit')->with('user', Auth::user());
    }

    /**
     * Updates user's account settings.
     *
     * @param
     * @return
     */
    public function update(Request $request)
    {
        // Load the user model (currently authenticated user)
        $user = Auth::user();

        $this->validate($request, [
            'email' => 'email|required|unique:users,email,'.$user->id,
            'telephone' => 'required|min:3|max:17',
        ]);

        // Update the user
        $user->update($request->all());

        flash()->success('Account Updated!', 'Settings updated successfully.');

        return redirect('account');
    }

    /**
     * Displays change password form the for authenticated user.
     *
     * @param
     * @return
     */
    public function password()
    {
        return view('account.password');
    }

    /**
     * Updates user's password.
     *
     * @param
     * @return
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [
            'old_password' => 'required|old_password:'.$user->password,
            'password' => 'required|confirmed|min:6',
        ]);

        $user->password = bcrypt($request->password);
        $user->update();

        flash()->success('Password Changed!', 'Password changed successfully.');

        return redirect('account');
    }
}
