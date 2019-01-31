<?php

namespace Trax\Account\Http\Controllers;

use Trax\Account\AccountServices;
use Trax\Account\Http\Validations\BasicClientValidation;

traxCreateStoreControllerSwitchClass('Trax\Account\Http\Controllers', 'BasicClient');

class BasicClientController extends BasicClientControllerSwitch
{
    use BasicClientValidation;


    /**
     * Create a new controller instance.
     */
    public function __construct(AccountServices $services)
    {
        $this->services = $services;
        $this->store = $this->services->basicClients();
    }
    
}
