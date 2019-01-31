<?php

namespace Trax\Notification\Permissions;

use Trax\Account\Permissions\PermissionModel;

class NotificationUserDefaultPermission extends PermissionModel
{

    /**
     * Mine function.
     */
    public function mine($request, $type, $user, $model, $id = null)
    {
        // No CREATE with this right
        if ($type == 'create') return false;

        // ID provided
        if (isset($id)) {
            $notif = (new $model)->find($id);
            return $notif->user_id == $user->id;
        }

        // Batch request
        return ['user_id' => $user->id];
    }

    /**
     * Get permissions.
     */
    public function subpermissions()
    {
        return [
            'Trax\Notification\Models\NotificationUser' => ['read:mine', 'update:mine', 'delete:mine'],
        ];
    }


}

