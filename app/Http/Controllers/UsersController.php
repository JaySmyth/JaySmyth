<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Role;
use App\Company;

class UsersController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Users Controller
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
     * Lists user accounts.
     *
     * @param
     * @return
     */
    public function index(Request $request)
    {
        $this->authorize(new User);

        $users = $this->search($request);

        return view('users.index', compact('users'));
    }

    /**
     * Displays a user record.
     *
     * @param
     * @return
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        $this->authorize($user);

        return view('users.show', compact('user'));
    }

    /**
     * Displays new user form.
     *
     * @param
     * @return
     */
    public function create()
    {
        $this->authorize(new User);

        $roles = Role::wherePrimary(0)->orderBy('label')->get();

        return view('users.create', compact('roles'));
    }

    /**
     * Saves a new user to the database.
     *
     * @param
     * @return
     */
    public function store(UserRequest $request)
    {
        $this->authorize(new User);

        // random password
        $password = str_random(8);

        $user = new User($request->all());
        $user->password = bcrypt($password);
        $user->api_token = strtolower(str_random(40));
        $user->show_search_bar = 1;

        // Not an IFS user, default users role to "cust"
        if (!$request->user()->hasIfsRole()) {
            $request->role_id = 1;
            $request->roles = [10];
        }

        $user->save();

        // Save the user's roles
        $user->syncRoles($request->roles, $request->role_id);

        // Add the company association
        $user->addCompany($request->company_id);

        // Only send email if checkbox ticked
        if ($request->send_email) {
            Mail::to($user->email)->bcc('cxadmin@antrim.ifsgroup.com')->queue(new \App\Mail\UserCreated($user, $password));
        }

        flash()->success('Created!', 'User created successfully.');

        return redirect('users/' . $user->id);
    }

    /**
     * Displays edit user form.
     *
     * @param
     * @return
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        $this->authorize($user);

        $roles = Role::wherePrimary(0)->orderBy('label')->get();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Updates an existing user.
     *
     * @param
     * @return
     */
    public function update(UserRequest $request, $id)
    {
        // Load the user model
        $user = User::findOrFail($id);

        $this->authorize($user);

        // Not an IFS user, default users role to "cust"
        if (!$request->user()->hasIfsRole()) {
            $request->role_id = 1;
            $request->roles = [10];
        }

        // Update the user
        $user->update($request->all());

        // Update the user's roles
        $user->syncRoles($request->roles, $request->role_id);

        flash()->success('Updated!', 'User updated successfully.');

        return redirect('users/' . $id);
    }

    /**
     * Displays add company to user page.
     *
     * @param
     * @return
     */
    public function addCompany($id)
    {
        $user = User::findOrFail($id);

        $this->authorize($user);

        return view('users.company', compact('user'));
    }

    /**
     * Saves a user/company relation.
     *
     * @param
     * @return
     */
    public function storeCompany(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->authorize('addCompany', $user);

        $user->addCompany($request->company_id);

        flash()->success('Company Added!', 'Company added successfully.');

        return redirect('users/' . $user->id);
    }

    /**
     * Detach a company from user.
     *
     * @param
     * @return
     */
    public function removeCompany($userId, $companyId)
    {
        $user = User::findOrFail($userId);

        $this->authorize($user);

        if ($user->companies()->count() > 1) {
            $user->companies()->detach($companyId);
            flash()->success('Company Removed!', 'Company removed successfully.');
            return back();
        }

        flash()->error('Cannot Remove!', 'User must be associated with at least one company.');

        return back();
    }

    /**
     * Display reset password form.
     *
     * @param
     * @return
     */
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        $this->authorize($user);

        return view('users.password', compact('user'));
    }

    /**
     * Updates user's password.
     *
     * @param
     * @return
     */
    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->authorize('resetPassword', $user);

        $this->validate($request, ['password' => 'required|confirmed|min:8']);

        $user->password = bcrypt($request->password);
        $user->update();

        // only send email if checkbox ticked
        if ($request->send_email) {
            Mail::to($user->email)->bcc($request->user()->email)->queue(new \App\Mail\PasswordReset($user, $request->password));
        }

        flash()->success('Password Reset!', 'Password changed successfully.');

        return redirect('users');
    }


    /**
     * Validates an api token/company code and returns the user/company details.
     *
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function validateUser(Request $request)
    {
        $this->validate($request, ['company_code' => 'required|size:6']);

        $user = $request->user();

        if ($user->hasIfsRole()) {
            $company = Company::where('company_code', $request->get('company_code'))->where('enabled', 1)->first();
        } else {
            $company = $user->companies->where('company_code', $request->get('company_code'))->where('enabled', 1)->first();
        }

        if ($company) {
            return response()->json([
                'name' => $user->name,
                'company_name' => $company->company_name,
                'address1' => $company->address1,
                'address2' => $company->address2,
                'city' => $company->city,
                'state' => $company->state,
                'postcode' => $company->postcode,
                'country_code' => $company->country_code,
                'email' => $user->email,
                'telephone' => $user->telephone
            ]);
        }

        return response()->json([
            'error' => 'Unauthenticated.'
        ], 401);
    }


    /*
     * User search.
     * 
     * @param   $request
     * @param   $paginate
     * 
     * @return
     */

    private function search($request)
    {
        // get an array of company IDs that the user has permission for
        $allowedCompanyIds = $request->user()->getAllowedCompanyIds()->toArray();

        $query = User::orderBy('name')
                ->filter($request->filter)
                ->hasEnabled($request->enabled)
                ->hasRole($request->role)
                ->restrictByCompany($allowedCompanyIds)
                ->with('companies');

        // if have been passed and company id and user has permission for it
        if (is_numeric($request->company) && in_array($request->company, $allowedCompanyIds)) {
            $query->where('company_user.company_id', '=', $request->company);
        }

        return $query->groupBy('id')->paginate(50);
    }

}
