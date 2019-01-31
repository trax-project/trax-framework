<?php

namespace Trax\Account\Http\Controllers;

use Trax\Account\AccountServices;
use Trax\Account\Http\Validations\AgreementValidation;

traxCreateStoreControllerSwitchClass('Trax\Account\Http\Controllers', 'Agreement');

class AgreementController extends AgreementControllerSwitch
{
    use AgreementValidation;


    /**
     * Create a new controller instance.
     */
    public function __construct(AccountServices $services)
    {
        $this->services = $services;
        $this->store = $this->services->agreements();
    }


}
