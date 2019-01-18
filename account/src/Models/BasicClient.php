<?php

namespace Trax\Account\Models;

traxCreateModelSwitchClass('Trax\Account\Models', 'trax-account', 'BasicClient');

class BasicClient extends BasicClientModel
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
    protected $table = 'trax_account_basic_clients';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'data', 'username', 'password',
    ];

    /**
     * The attributes that should be visible.
     */
    protected $visible = [
        'id', 'username', 'password', 'data', 'created_at', 'updated_at'
    ];

    /**
     * Definition, needed by Trax Data Stores.
     */
    protected $trax = [
        'columns' => ['username', 'password'],
    ];

}
