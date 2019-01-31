<?php

namespace Trax\Notification;

use Event;

use Trax\DataStore\DataStoreServiceProvider;

class NotificationServiceProvider extends DataStoreServiceProvider
{
    /**
     * Plugin code. 
     */
    protected $plugin = 'trax-notification';

    /**
     * Namespace. 
     */
    protected $namespace = __NAMESPACE__;

    /**
     * Directory. 
     */
    protected $dir = __DIR__;

    /**
     * Register UI.
     */
    protected $hasUI = true;

    /**
     * Models > Tables.
     */
    protected $models = [
        'Notification' => 'trax_notification',
        'NotificationUser' => 'trax_notification_user',
    ];

    /**
     * The service provider commands.
     */
    protected $commands = [
        'Trax\Notification\Console\DailyDigestCommand',
    ];
    

    /**
     * Register services.
     */
    protected function registerServices()
    {
        $plugin = $this->plugin;
        $models = $this->models;
        $this->app->singleton('Trax\Notification\NotificationServices', function ($app) use ($plugin, $models) {
            return new NotificationServices($app, $plugin, $models);
        });
    }

    /**
     * Register events.
     */
    protected function registerEvents()
    {
        $services = $this->app->make('Trax\Notification\NotificationServices');
        Event::listen('*', function ($eventName, array $data) use ($services) {
            $services->listener()->listen($eventName, $data);
        });
    }


}
