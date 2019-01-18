<?php

namespace Trax\Account\Permissions;

class AgreementWritePermission extends AgreementDefaultPermission
{

    /**
     * Get permissions.
     */
    public function subpermissions()
    {
        return [
            'Trax\Account\Models\Agreement' => ['create', 'read'],
        ];
    }


}

