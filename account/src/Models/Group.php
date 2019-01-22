<?php

namespace Trax\Account\Models;

traxCreateModelSwitchClass('Trax\Account\Models', 'trax-account', 'Group');

class Group extends GroupModel
{

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'data' => 'object',
    ];

    /**
     * The table associated with the model.
     */
    protected $table = 'trax_account_groups';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'uuid', 'data', 
    ];

    /**
     * The attributes that should be visible.
     */
    protected $visible = [
        'id', 'uuid', 'status', 'data', 'created_at', 'updated_at'
    ];

    /**
     * Definition, needed by Trax Data Stores.
     */
    protected $trax = [
        'columns' => ['uuid'],
        'protected' => ['uuid'],
        'virtualColumns' => [
            'data.name' => 'name'
        ],
        'options' => [
            'status' => [
                'key' => 'data.status_code',
                'model' => 'Trax.StandardStatus',
            ],
        ],
        'relations' => [
            'users' => 'multiple',
        ],
        'defaultValues' => [
            'uuid' => ['type' => 'function', 'default' => 'traxUuid'],
        ],
    ];

    /**
     * The registered users.
     */
    public function users()
    {
        return $this->belongsToMany('Trax\Account\Models\User', 'trax_account_group_user');
    }

    /**
     * Registrations.
     */
    public function registrations()
    {
        if (!config('trax-training.enabled')) return $this;
        return $this->hasMany('Trax\Training\Models\GroupRegistration');
    }

}
