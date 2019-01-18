<?php

namespace Trax\Account\Permissions;

class RoleCrudPermission extends RoleDefaultPermission
{

    /**
     * Get permissions.
     */
    public function subpermissions()
    {
        return [
            'Trax\Account\Models\Role' => ['create', 'read', 'update', 'delete'],
        ];
    }

}

