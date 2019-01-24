<?php

namespace Trax\Account\Models;

traxCreateModelSwitchClass('Trax\Account\Models', 'trax-account', 'Role');

class Role extends RoleModel
{

    /**
     * The table associated with the model.
     */
    protected $table = 'trax_account_roles';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'data',
    ];

    /**
     * The attributes that should be visible.
     */
    protected $visible = [
        'id', 'data', 'created_at', 'updated_at'
    ];

    /**
     * Definition, needed by Trax Data Stores.
     */
    protected $trax = [
        'virtualColumns' => [
            'data.name' => 'name'
        ],
    ];


    /**
     * Get the role permissions
     */
    public function getPermissionsAttribute($value)
    {
        return isset($this->data->permissions) ? $this->data->permissions : [];
    }

}
