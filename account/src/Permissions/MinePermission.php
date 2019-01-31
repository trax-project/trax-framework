<?php

namespace Trax\Account\Permissions;

trait MinePermission
{

    /**
     * Mine function with callback.
     */
    protected function mineCallback($getIdsFunction, $request, $type, $user, $model, $id = null)
    {
        // No CREATE with this right
        if ($type == 'create') return false;

        // Get group IDs
        $ids = $getIdsFunction();

        // ID provided
        if (isset($id)) return in_array($id, $ids);

        // Batch request
        return [
            (object)array('key' => 'id', 'operator' => 'IN', 'value' => $ids)
        ];
    }

}

