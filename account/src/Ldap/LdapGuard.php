<?php

namespace Trax\Account\Ldap;

use Illuminate\Auth\SessionGuard;

class LdapGuard extends SessionGuard
{

    /**
     * Determine if the user matches the credentials.
     */
    protected function hasValidCredentials($user, $credentials)
    {
        if (is_null($user)) return false;

        // Internal authentication
        if ($user->source_code == 'internal') return parent::hasValidCredentials($user, $credentials);

        // LDAP authentication
        return (new LdapConnector)->validateCredentials($user, $credentials);
    }


}
