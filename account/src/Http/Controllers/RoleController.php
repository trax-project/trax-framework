<?php

namespace Trax\Account\Http\Controllers;

use Trax\Account\AccountServices;
use Trax\Account\Http\Validations\RoleValidation;

traxCreateStoreControllerSwitchClass('Trax\Account\Http\Controllers', 'Role');

class RoleController extends RoleControllerSwitch
{
    use RoleValidation;

    /**
     * Delete exception message key.
     */
    protected $deleteExceptionMessageKey = 'trax-account::common.role_deletion_exception';


    /**
     * Create a new controller instance.
     */
    public function __construct(AccountServices $services)
    {
        $this->services = $services;
        $this->store = $this->services->roles();
    }
    
}
