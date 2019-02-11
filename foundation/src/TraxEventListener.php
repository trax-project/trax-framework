<?php

namespace Trax\Foundation;

class TraxEventListener
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
    public function __construct($services)
    {
        $this->services = $services;
    }

    /**
     * Listen events.
     */
    public function listen($eventName, array $data)
    {
        if (!isset($this->events[$eventName])) return;
        $handlers = is_array($this->events[$eventName]) ? $this->events[$eventName] : array($this->events[$eventName]);
        foreach($handlers as $handler) {
            (new $handler($this->services, $data[0]))->handle();
        }
    }

}
