<?php

namespace Trax\Account\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

use TraxAccount;

class InvitationController extends ForgotPasswordController
{

    /**
     * Send a reset link to the given user.
     */
    public function sendInvitationEmail(Request $request)
    {
        // Check user admin permission
        TraxAccount::authorizer()->allows('trax_account_user_crud');

        return parent::sendResetLinkEmail($request);
    }

    /**
     * Hook after sending a reset link.
     */
    protected function sendResetLinkEmailAfterHook($user)
    {
        $user->setStatus('invited');
    }

    /**
     * Get the response for a successful password reset link.
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response(__('trax-account::common.sent_invitation_email_success'), 200);
    }

    /**
     * Get the response for a failed password reset link.
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response(__('trax-account::common.sent_invitation_email_error'), 401);
    }

    /**
     * Get the broker to be used during password reset.
     */
    public function broker()
    {
        return Password::broker('users_invitations');
    }

}
