<?php

namespace Trax\Account\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Controllers\Auth\ResetPasswordController as NativeResetPasswordController;

class ResetPasswordController extends NativeResetPasswordController
{

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

}
