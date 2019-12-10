<?php

namespace Trax\Account\Permissions;

class RoleDefaultPermission extends PermissionModel
{
    use MinePermission;

    /**
     * Mine function.
     */
    public function mine($request, $type, $user, $model, $id = null)
    {
        // No CREATE with this right
        if ($type == 'create') return false;

        // ID provided
        if (isset($id)) return $id == $user->role_id;

        // Batch request
        return ['id' => $user->role_id];
    }

    /**
     * Get permissions.
     */
    public function subpermissions()
    {
        return [
            'Trax\Account\Models\Role' => ['read'],
        ];
    }


}

