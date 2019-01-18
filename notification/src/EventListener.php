<?php

namespace Trax\Notification;

class EventListener
{
    /**
     * Services.
     */
    protected $services;

    /**
     * Events to listen.
     */
    protected $events = [];


    /**
     * Construct.
     */
    public function __construct(NotificationServices $services)
    {
        $this->services = $services;
    }

    /**
     * Listen events.
     */
    public function listen($eventName, array $data)
    {
        if (!isset($this->events[$eventName])) return;
        $notifications = is_array($this->events[$eventName]) ? $this->events[$eventName] : array($this->events[$eventName]);
        foreach($notifications as $notification) {
            (new $notification($this->services, $data[0]))->sendInternal();
        }
    }

}
