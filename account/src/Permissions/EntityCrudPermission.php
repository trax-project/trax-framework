<?php

namespace Trax\Account\Permissions;

class EntityCrudPermission extends EntityDefaultPermission
{

    /**
     * Get permissions.
     */
    public function subpermissions()
    {
        return [
            'Trax\Account\Models\Entity' => ['create', 'read', 'update', 'delete'],
        ];
    }


}

