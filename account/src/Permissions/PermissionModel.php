<?php

namespace Trax\Account\Permissions;

abstract class PermissionModel
{
    /**
     * Permissions manager.
     */
    protected $manager;


    /**
     * Construct.
     */
    public function __construct($manager)
    {
        $this->manager = $manager;
    }

    /**
     * Get permissions.
     */
    public function subpermissions()
    {
        return [];
    }

    /**
     * Get specific rights.
     */
    public function getRights($type, $model)
    {
        $sub = $this->subpermissions();
        if (!isset($sub[$model])) return [];
        return array_filter($sub[$model], function($item) use ($type)  {
            $parts = explode(':', $item);
            return $parts[0] == $type;
        });
    }


}

