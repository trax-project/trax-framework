<?php

namespace Trax\Account\Permissions;

class GroupDefaultPermission extends PermissionModel
{
    use MinePermission;

    /**
     * Mine function.
     */
    public function mine($request, $type, $user, $model, $id = null)
    {
        return $this->mineCallback(function() use ($request, $type, $user, $model, $id) {

            return $user->groups()->pluck('id')->toArray();

        }, $request, $type, $user, $model, $id);
    }

    /**
     * Get permissions.
     */
    public function subpermissions()
    {
        return [
            'Trax\Account\Models\Group' => ['read:mine'],
        ];
    }


}

