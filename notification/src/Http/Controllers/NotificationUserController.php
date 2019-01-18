<?php

namespace Trax\Notification\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use Trax\Notification\NotificationServices;

traxCreateStoreControllerSwitchClass('Trax\Notification\Http\Controllers', 'NotificationUser');

class NotificationUserController extends NotificationUserControllerSwitch
{

    /**
     * Create a new controller instance.
     */
    public function __construct(NotificationServices $services)
    {
        $this->services = $services;
        $this->store = $this->services->notificationUsers();
    }

    /**
     * Validate get request.
     */
    protected function validateGetRequest(Request $request)
    {
        parent::validateGetRequest($request);
        $this->filters['user_id'] = Auth::user()->id;
    }


}
