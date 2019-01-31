<?php

namespace Trax\Account\Http\Controllers;

use Trax\Account\AccountServices;
use Trax\Account\Http\Validations\EntityValidation;

traxCreateStoreControllerSwitchClass('Trax\Account\Http\Controllers', 'Entity');

class EntityController extends EntityControllerSwitch
{
    use EntityValidation;

    /**
     * Delete exception message key.
     */
    protected $deleteExceptionMessageKey = 'trax-account::common.entity_deletion_exception';


    /**
     * Create a new controller instance.
     */
    public function __construct(AccountServices $services)
    {
        $this->services = $services;
        $this->store = $this->services->entities();
    }
    
}
