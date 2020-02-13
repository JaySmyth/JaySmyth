<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\Store;

class SessionTimeout
{
    protected $session;
    protected $timeout = 3600;

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // Are we trying to access a protected page
        $protectedURL = $request->path() != 'login';

        if (! session('lastActivityTime')) {

            // If not defined then define lastActivityTime
            $this->session->put('lastActivityTime', time());
        } else {
            if (time() - $this->session->get('lastActivityTime') > $this->timeout) {

                // Session has timed out
                $this->session->forget('lastActivityTime');

                // Log the user out and forward to login page with error
                auth()->logout();

                return redirect('/login')->withInput()->withErrors(['config' => 'Session timeout - No activity for '.$this->timeout / 60 .' minutes.']);
            }
        }

        // Update lastActivityTime
        $protectedURL ? $this->session->put('lastActivityTime', time()) : $this->session->forget('lastActivityTime');

        return $next($request);
    }
}
