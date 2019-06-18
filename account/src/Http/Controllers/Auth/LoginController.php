<?php

namespace Trax\Account\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController as NativeLoginController;

class LoginController extends NativeLoginController
{
    /**
     * Get the login username to be used by the controller.
     */
    public function username()
    {
        return config('trax-account.auth.username') ? 'username' : 'email';
    }

    /**
     * Get the needed authorization credentials from the request.
     */
    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        $credentials['active'] = 1;
        return $credentials;
    }

    /**
     * The user has been authenticated.
     */
    protected function authenticated(Request $request, $user)
    {
        $user->setStatus('loggedin');
    }


}
