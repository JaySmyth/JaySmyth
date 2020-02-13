<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Arr;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class LoginController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Override getCredentials method to include account enabled check.
     *
     * @param  array  $data
     * @return User
     */
    public function getCredentials($request)
    {
        $credentials = $request->only($this->loginUsername(), 'password');

        return Arr::add($credentials, 'enabled', '1');
    }

    /**
     * Override authenticated method to check user config.
     */
    public function authenticated(Request $request, $user)
    {
        if ($user->isConfigured()) {
            $this->updateLastLogin($request, $user);

            $this->flashMessages($user);

            // User configured as Duty and VAT only - redirect to customs entries
            if ($user->hasRole('cudv') || $user->hasRole('ifsc')) {
                return redirect('/customs-entries');
            }

            if (! $user->hasMultipleModes() && $user->getOnlyMode() == 'sea') {
                return redirect('/sea-freight');
            }

            return redirect()->intended($this->redirectPath());
        }

        // Account not configured, so logout and redirect
        $this->logout($request);

        return redirect('/login')->withErrors([
                    'config' => 'Sorry, account not configured. Please contact IFS.',
        ]);
    }

    /**
     * Update the last login timestamp.
     *
     * @param type $user
     */
    private function updateLastLogin($request, $user)
    {
        $agent = new Agent();
        $browser = $agent->browser();

        $user->last_login = time();
        $user->browser = $browser.' '.$agent->version($browser);
        $user->platform = $agent->platform();
        $user->screen_resolution = $request->screen_resolution;
        $user->save();
    }

    /**
     * Flash any notification messages to the user.
     *
     * @param type $param
     */
    private function flashMessages($user)
    {
        // Check for dated browser (IFS staff only)
        if (stristr($user->browser, 'IE ') && $user->hasIfsRole()) {
            flash()->warning('Dated browser detected', 'We recommend switching to the latest version of Firefox or Microsoft Edge.', true);
        }

        foreach ($user->getMessages() as $message) {

            // If sticky OR not previously viewed by the user, display the message
            if ($message->sticky || (! $message->sticky && $message->users()->where('user_id', $user->id)->count() == 0)) {
                flash()->info($message->title, $message->message, true, true);
            }

            // Insert a record to indicate that the message has been view by the user
            $message->users()->syncWithoutDetaching($user->id);
        }
    }
}
