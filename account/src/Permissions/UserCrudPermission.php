<?php

namespace Trax\Account\Permissions;

class UserCrudPermission extends UserDefaultPermission
{

    /**
     * Get permissions.
     */
    public function subpermissions()
    {
        return [
            'Trax\Account\Models\User' => ['create', 'read', 'update', 'delete'],
            'Trax\Account\Models\Entity' => ['read'],
        ];
    }

}

