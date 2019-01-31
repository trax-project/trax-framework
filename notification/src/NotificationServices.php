<?php

namespace Trax\Notification;

use Trax\DataStore\DataStoreServices;

class NotificationServices extends DataStoreServices
{
    /**
     * Namespace.
     */
    protected $namespace = __NAMESPACE__;

    /**
     * Event listener.
     */
    protected $listener;


    /**
     * Init.
     */
    protected function init()
    {
        $listener = config('trax-notification.listener');
        $this->listener = new $listener($this);
    }

    /**
     * Get Event Listener.
     */
    public function listener()
    {
        return $this->listener;
    }

    /**
     * Get Notification store.
     */
    public function notifications()
    {
        return $this->store('Notification');
    }

    /**
     * Get NotificationUser store.
     */
    public function notificationUsers()
    {
        return $this->store('NotificationUser');
    }
    
}
