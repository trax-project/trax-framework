<?php

namespace Trax\Notification\Notifications;

use Illuminate\Notifications\Notification as NativeNotification;

abstract class EventNotification extends NativeNotification
{
    /**
     * Services.
     */
    protected $services;

    /**
     * Event.
     */
    protected $event;

    /**
     * Notification title.
     */
    protected $title;

    /**
     * Notification message.
     */
    protected $message;

    /**
     * Notification message context.
     */
    protected $context;

    /**
     * Notification message styled context.
     */
    protected $styledContext;

    /**
     * Notification action label.
     */
    protected $actionLabel;

    /**
     * Notification action color.
     */
    protected $actionColor = 'primary';


    /**
     * Construct.
     */
    public function __construct($services, $event)
    {
        $this->services = $services;
        $this->event = $event;
        $this->init();
    }

    /**
     * Init hook.
     */
    protected function init()
    {
    }

    /**
     * Handle the event.
     */
    public function handle()
    {
        $this->sendInternal();
    }

    /**
     * Store the notification.
     */
    public function sendInternal()
    {
        // Store the notification
        $record = $this->defineRecord();
        $notif = $this->services->notifications()->store($record, ['format' => 'object']);

        // Associate to recipients
        $userIds = $this->defineRecipients();
        $notif->users()->attach($userIds, ['data' => '{}']);
    }

    /**
     * Define the notification record.
     */
    protected function defineRecord()
    {
        return [
            'title' => $this->defineTitle(),
            'context' => $this->defineContext(),
            'styled_context' => $this->defineStyledContext(),
            'message' => $this->defineMessage(),
            'action' => $this->defineAction(),
        ];
    }

    /**
     * Define title.
     */
    protected function defineTitle()
    {
        return $this->title;
    }

    /**
     * Define context.
     */
    protected function defineContext()
    {
        return $this->context;
    }

    /**
     * Define styled context.
     */
    protected function defineStyledContext()
    {
        return $this->styledContext;
    }

    /**
     * Define message.
     */
    protected function defineMessage()
    {
        return $this->message;
    }

    /**
     * Define action.
     */
    protected function defineAction()
    {
        return [
            'label' => $this->actionLabel,
            'color' => $this->actionColor,
            'url' => $this->defineActionUrl(),
        ];
    }

    /**
     * Define action.
     */
    protected function defineActionUrl()
    {
    }

    /**
     * Define recipients.
     */
    protected function defineRecipients()
    {
        return [];
    }

}