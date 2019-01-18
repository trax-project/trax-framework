<?php

namespace Trax\UI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use Trax\Foundation\TraxServices;
use Trax\UI\UIServices;
use Trax\Account\AccountServices;
use Trax\Notification\NotificationServices;

class ViewsController extends Controller
{
    /**
     * Foundation services
     */
    protected $trax;

    /**
     * UI services
     */
    protected $ui;

    /**
     * Account services.
     */
    protected $account;

    /**
     * Notification services.
     */
    protected $notification;

    /**
     * Nav object
     */
    protected $nav;


    /**
     * Create a new controller instance.
     */
    public function __construct(TraxServices $trax, UIServices $ui, AccountServices $account, NotificationServices $notification)
    {
        $this->middleware('account.agreement', ['except' => 'agreementApprove']);
        $this->trax = $trax;
        $this->ui = $ui;
        $this->account = $account;
        $this->notification = $notification;
        $this->nav = (object)array();
    }

    /**
     * Return a view with data.
     */
    protected function view($name, $data = [], $moreData = [])
    {
        // Prepare data
        if (is_array($data)) $data = (object)$data;
        if (config('trax-notification.ui.enabled')) $data->notifications = $this->notificationsData();

        // Prepare nav
        $userData = Auth::user()->data;
        $this->nav->minisidebar = isset($userData->preferences) && isset($userData->preferences->minisidebar) && $userData->preferences->minisidebar;
        
        // View with merge data
        $readyData = array_merge(['nav' => $this->nav, 'data' => $data], $moreData);
        return view($name, $readyData);
    }

    /**
     * Return a view with data.
     */
    protected function notificationsData()
    {
        return $this->notification->notificationUsers()->get(
            [
                'search' => [
                    'user_id' => Auth::user()->id,
                    (object)['key' => 'data.read', 'operator' => 'NULL']
                ],
                'order-by' => 'id',
                'order-dir' => 'desc',
            ],
            ['with' => ['notification']]
        );
    }

}
