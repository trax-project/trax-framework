<?php

namespace Trax\Account;

class PermissionsManager
{
    /**
     * Permissions.
     */
    protected $permissions = [];


    /**
     * Register permissions.
     */
    public function register($permissions, $model = null)
    {
        $this->permissions = array_merge($this->permissions, $permissions);
    }

    /**
     * Get select data.
     */
    public function select()
    {
        $res = [];
        foreach ($this->permissions as $key => $data) {
            if (isset($data['model'])) continue;
            $res[] = ['value' => $key, 'name' => $data['name']];
        }
        return $res;
    }

    /**
     * Return default values.
     */
    public function defaultValues($default)
    {
        $res = [];
        foreach ($this->permissions as $key => $data) {
            if (isset($data['model'])) continue;
            $res[$key] = $default;
        }
        return $res;
    }

    /**
     * Check permission.
     * When ID is null, returns $args to perform the query.
     */
    public function check($request, $type, $user, $model, $id = null)
    {
        $permissions = $user->role->permissions;
        $permissions = $this->filterPermissions($permissions);
        list($permission, $rights) = $this->getPermissionAndRights($type, $model, $permissions);

        // Check it
        if (empty($rights) || !$permission) return false;
        if (in_array($type, $rights)) return true;

        $searchOr = [];
        foreach($rights as $right) {
            $parts = explode(':', $right);
            $function = $parts[1];
            $res = $permission->$function($request, $type, $user, $model, $id);
            if (is_array($res)) $searchOr[] = $res;
            else if ($res) return true;
        }
        if (!count($searchOr)) return false;
        return [$searchOr];
    }

    /**
     * Filter permissions.
     */
    protected function filterPermissions($userPermissions)
    {
        $res = [];

        // Add assigned permissions
        $permissions = array_filter((array)$userPermissions);
        foreach ($permissions as $code => $true) {
            if (isset($this->permissions[$code])) $res[$code] = $this->permissions[$code];
        }

        // Add default permissions
        foreach ($this->permissions as $code => $permission) {
            if (isset($permission['model'])) $res[$code] = $permission;
        }

        return $res;
    }

    /**
     * Get all rights.
     */
    protected function getPermissionAndRights($type, $model, $permissions)
    {
        $rights = [];
        $permObject = false;
        foreach ($permissions as $code => $permission) {
            $class = $permission['class'];
            $object = new $class($this);
            $permRights = $object->getRights($type, $model);
            $rights = array_merge($rights, $permRights);
            if (isset($permission['model']) && $permission['model'] == $model) $permObject = $object;
        }
        $rights = array_unique($rights);
        return [$permObject, $rights];
    }

}

