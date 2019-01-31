<?php

namespace Trax\Account;

abstract class PermissionsRegistrar
{

    /**
     * Get permissions.
     */
    protected abstract function permissions();

    /**
     * Register permissions.
     */
    public function register($account)
    {
        $account->registerPermissions($this->permissions());
    }

}

