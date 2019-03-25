<?php

namespace Trax\Account\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Auth\ResetPasswordController as NativeResetPasswordController;

use TraxAccount;

class ResetPasswordController extends NativeResetPasswordController
{
    /**
     * User.
     */
    protected $user;


    /**
     * Reset the given user's password.
     */
    public function reset(Request $request)
    {
        try {
            $this->user = TraxAccount::users()->findBy('email', $request->input('email'));
        } catch (\Exception $e) {
        }
        return parent::reset($request);
    }

    /**
     * Reset the given user's password.
     */
    protected function resetPassword($user, $password)
    {
        parent::resetPassword($user, $password);
        if ($user->getStatus('password_defined')) {
            $user->setStatus('password_reset');
            $user->sendPasswordResetAlert();
        } else {
            $user->setStatus('password_defined');
            $user->sendPasswordDefined();
        }
    }

    /**
     * Get the response for a failed password reset.
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response(__('trax-account::common.reset_password_error'), 401);
    }

    /**
     * Get the broker to be used during password reset.
     */
    public function broker()
    {
        if (isset($this->user) && $this->user->getStatus('password_defined')) {
            return Password::broker();
        } else {
            return Password::broker('users_invitations');
        }
    }

}
