<?php

namespace Trax\Account\Permissions;

class GroupCrudPermission extends GroupDefaultPermission
{

    /**
     * Get permissions.
     */
    public function subpermissions()
    {
        return [
            'Trax\Account\Models\Group' => ['create', 'read', 'update', 'delete'],
            'Trax\Account\Models\User' => ['read'],
            'Trax\Account\Models\Entity' => ['read'],
        ];
    }


}

