<?php

namespace Trax\Account;

use Trax\Account\Exceptions\ForbiddenException;
use Trax\Account\Models\User;

use Auth;

class Authorizer
{
    /**
     * Check a permission, and throw an unauthorized exception when needed.
     */
    public function allows($permission, $user = null)
    {
        if (!$this->allowed($permission, $user))
            throw new ForbiddenException();
    }

    /**
     * Check alternative permissions, and throw an unauthorized exception when needed.
     */
    public function allowsOr($permissions, $user = null)
    {
        if (!$this->allowedOr($permissions, $user))
            throw new ForbiddenException();
    }

    /**
     * Check cumulative permissions, and throw an unauthorized exception when needed.
     */
    public function allowsAnd($permissions, $user = null)
    {
        if (!$this->allowedAnd($permissions, $user))
            throw new ForbiddenException();
    }

    /**
     * Check a permission.
     */
    public function allowed($permission, $user = null)
    {
        $user = $this->getUser($user);
        if ($user->admin) return true;
        if (!$user->role) return false;
        return isset($user->role->permissions->$permission) && $user->role->permissions->$permission;
    }

    /**
     * Check alternative permissions.
     */
    public function allowedOr($permissions, $user = null)
    {
        foreach ($permissions as $permission) {
            if ($this->allowed($permission, $user)) return true;
        }
        return false;
    }

    /**
     * Check cumulative permissions.
     */
    public function allowedAnd($permissions, $user = null)
    {
        foreach ($permissions as $permission) {
            if (!$this->allowed($permission, $user)) return false;
        }
        return true;
    }

    /**
     * Get the involved user.
     */
    public function getUser($user = null)
    {
        if (!isset($user)) return Auth::user();
        if (is_integer($user)) return User::with('role')->findOrFail($user);
        return $user;
    }

}
