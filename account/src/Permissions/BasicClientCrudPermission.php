<?php

namespace Trax\Account\Permissions;

class BasicClientCrudPermission extends BasicClientDefaultPermission
{
    
    /**
     * Get permissions.
     */
    public function subpermissions()
    {
        return [
            'Trax\Account\Models\BasicClient' => ['create', 'read', 'update', 'delete'],
        ];
    }

}

