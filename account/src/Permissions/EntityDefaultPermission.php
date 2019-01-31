<?php

namespace Trax\Account\Permissions;

class EntityDefaultPermission extends PermissionModel
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
        if (isset($id)) return $id = $user->entity_id || $id = $user->organization_id;

        // User has no organization & no entity
        if (!isset($user->organization_id) || !$user->organization_id) return false;

        // User has no entity
        if (!isset($user->entity_id) || !$user->entity_id) return ['id' => $user->organization_id];

        // User has an organization and an entity
        return [
            (object)array('key' => 'id', 'operator' => 'IN', 'value' => [$user->entity_id, $user->organization_id])
        ];
    }

    /**
     * Mine collaborators function.
     */
    public function collaborators($request, $type, $user, $model, $id = null)
    {
        return $this->mineCallback(function () use ($request, $type, $user, $model, $id) {

            return $user->entityIds();

        }, $request, $type, $user, $model, $id);
    }

    /**
     * Mine locations function.
     */
    public function locations($request, $type, $user, $model, $id = null)
    {
        return $this->mineCallback(function () use ($request, $type, $user, $model, $id) {

            return $user->entityIds(true);

        }, $request, $type, $user, $model, $id);
    }

    /**
     * Get permissions.
     */
    public function subpermissions()
    {
        return [
            'Trax\Account\Models\Entity' => ['read:mine'],
        ];
    }


}

