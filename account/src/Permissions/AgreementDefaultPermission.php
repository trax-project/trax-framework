<?php

namespace Trax\Account\Permissions;

class AgreementDefaultPermission extends PermissionModel
{

    /**
     * Get permissions.
     */
    public function subpermissions()
    {
        return [
            'Trax\Account\Models\Agreement' => ['read'],
        ];
    }


}

