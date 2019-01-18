<?php

namespace Trax\Account\Http\Controllers;

use Trax\Account\AccountServices;
use Trax\DataStore\Http\Controllers\DataRelationController;
use Trax\Account\Http\Guards\GroupGuard;

traxCreateStoreControllerSwitchClass('Trax\Account\Http\Controllers', 'GroupUser');

class GroupUserController extends GroupUserControllerSwitch
{
    use DataRelationController, GroupGuard;

    /**
     * Left store name.
     */
    protected $leftStoreName = 'groups';

    /**
     * Right store name.
     */
    protected $rightStoreName = 'users';


    /**
     * Create a new controller instance.
     */
    public function __construct(AccountServices $services)
    {
        $this->initStores($services);
    }

}
