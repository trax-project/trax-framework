<?php

namespace Trax\Account\Http\Controllers;

use Trax\Account\AccountServices;
use Trax\DataStore\Http\Controllers\DataRelationController;

traxCreateStoreControllerSwitchClass('Trax\Account\Http\Controllers', 'AgreementUser');

class AgreementUserController extends AgreementUserControllerSwitch
{
    use DataRelationController;

    /**
     * Left store name.
     */
    protected $leftStoreName = 'users';

    /**
     * Right store name.
     */
    protected $rightStoreName = 'agreements';

    /**
     * Pivot created_at.
     */
    protected $pivotCreatedAt = true;


    /**
     * Create a new controller instance.
     */
    public function __construct(AccountServices $services)
    {
        $this->initStores($services);
    }

}
