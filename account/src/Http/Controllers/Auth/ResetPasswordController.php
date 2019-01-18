<?php

namespace Trax\Account\Http\Controllers\Auth;

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


}
