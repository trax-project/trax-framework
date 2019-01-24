<?php

namespace Trax\Account\Models;

traxCreateModelSwitchClass('Trax\Account\Models', 'trax-account', 'Agreement');

class Agreement extends AgreementModel
{
    /**
     * The table associated with the model.
     */
    protected $table = 'trax_account_agreements';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'content', 'published', 'data', 
    ];

    /**
     * The attributes that should be visible.
     */
    protected $visible = [
        'id', 'content', 'published', 'data', 'created_at', 'updated_at'
    ];

    /**
     * Definition, needed by Trax Data Stores.
     */
    protected $trax = [
        'columns' => ['content', 'published'],
    ];


    /**
     * The registered users.
     */
    public function users()
    {
        return $this->belongsToMany('Trax\Account\Models\User', 'trax_account_agreement_user');
    }


}
