<?php

namespace Trax\Account\Http\Controllers;

use Trax\Account\AccountServices;
use Trax\Account\Http\Validations\GroupValidation;
use Trax\Account\Http\Guards\GroupGuard;

traxCreateStoreControllerSwitchClass('Trax\Account\Http\Controllers', 'Group');

class GroupController extends GroupControllerSwitch
{
    use GroupValidation, GroupGuard;

    /**
     * Delete exception message key.
     */
    protected $deleteExceptionMessageKey = 'trax-account::common.group_deletion_exception';


    /**
     * Create a new controller instance.
     */
    public function __construct(AccountServices $services)
    {
        $this->services = $services;
        $this->store = $this->services->groups();
    }


}
