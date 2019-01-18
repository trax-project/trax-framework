<?php

namespace Trax\Account\Http\Controllers\Auth;

use App\Http\Controllers\Auth\ForgotPasswordController as NativeForgotPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends NativeForgotPasswordController
{

    /**
     * Override the native constructor to remove the guest middleware, which is defined in the routes.
     */
    public function __construct()
    {
    }

    /**
     * Send a reset link to the given user.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // Get only active and internal user
        $credentials = array_merge($request->only('email'), ['active' => 1, 'source_code' => 'internal']);
        $response = $this->broker()->sendResetLink($credentials);

        // After hook
        if ($response == Password::RESET_LINK_SENT) {
            $user = $this->broker()->getUser($credentials);
            $this->sendResetLinkEmailAfterHook($user);
        }

        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }

    /**
     * Hook after sending a reset link.
     */
    protected function sendResetLinkEmailAfterHook($user)
    {
    }

    /**
     * Get the response for a successful password reset link.
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response(__('trax-account::common.sent_password_email_success'), 200);
    }

    /**
     * Get the response for a failed password reset link.
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response(__('trax-account::common.sent_password_email_error'), 401);
    }


}
