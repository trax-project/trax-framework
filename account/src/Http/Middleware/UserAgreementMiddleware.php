<?php

namespace Trax\Account\Http\Middleware;

use TraxAccount;
use Auth;

class UserAgreementMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, $next, $guard = null) 
    {
        // Agreements deactivated
        if (!config('trax-account.services.agreements')) return $next($request);

        // Unauthenticated views
        if (!Auth::check()) return $next($request);

        // No agreement to sign
        $lastAgreement = TraxAccount::agreements()->lastPublished();
        if (!$lastAgreement) return $next($request);

        // No agreement signed, or new agreement to sign
        $signedAgreement = Auth::user()->agreements->last();
        if (!$signedAgreement || $signedAgreement->id < $lastAgreement->id) {
            return redirect()->route('trax.ui.account.agreement.approve');
        }

        // Fine, we continue
        return $next($request);
    }

}

