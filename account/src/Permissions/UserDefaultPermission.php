<?php

namespace Trax\Account\Permissions;

use Trax\Account\Models\Group;

class UserDefaultPermission extends PermissionModel
{
    use MineRegistrationPermission;

    /**
     * Mine function.
     */
    public function mine($request, $type, $user, $model, $id = null)
    {
        // No CREATE with this right
        if ($type == 'create') return false;

        // ID provided
        if (isset($id)) return $id == $user->id;

        // Batch request
        return ['id' => $user->id];
    }

    /**
     * Mine collaborators function.
     */
    public function collaborators($request, $type, $user, $model, $id = null)
    {
        // No CREATE with this right
        if ($type == 'create') return false;

        // Get collaborator IDs
        $ids = $user->collaboratorIds();

        // ID provided
        if (isset($id)) return in_array($id, $ids);
        
        // Bulk request
        return [
            (object)array('key' => 'id', 'operator' => 'IN', 'value' => $ids)
        ];
    }

    /**
     * Mine locations function.
     */
    public function locations($request, $type, $user, $model, $id = null)
    {
        return $this->mineRegistrationCallback(function () use ($request, $type, $user, $model, $id) {

            return $this->registration($request)->group->users()->pluck('id')->toArray();

        }, $request, $type, $user, $model, $id);
    }

    /**
     * Get permissions.
     */
    public function subpermissions()
    {
        return [
            'Trax\Account\Models\User' => ['read:mine', 'update:mine'],
        ];
    }


}

