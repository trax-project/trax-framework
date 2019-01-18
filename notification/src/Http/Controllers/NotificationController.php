<?php

namespace Trax\Notification\Http\Controllers;

use Illuminate\Http\Request;

use Trax\Notification\NotificationServices;

traxCreateStoreControllerSwitchClass('Trax\Notification\Http\Controllers', 'Notification');

class NotificationController extends NotificationControllerSwitch
{

    /**
     * Create a new controller instance.
     */
    public function __construct(NotificationServices $services)
    {
        $this->services = $services;
        $this->store = $this->services->notifications();
    }
    
}
