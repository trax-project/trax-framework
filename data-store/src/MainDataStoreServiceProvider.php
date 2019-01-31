<?php

namespace Trax\DataStore;

use Trax\DataStore\Validators\DataValidator;
use Trax\DataStore\Http\Middleware\CorsMiddleware;

class MainDataStoreServiceProvider extends DataStoreServiceProvider
{
    /**
     * Plugin code. 
     */
    protected $plugin = 'trax-data-store';

    /**
     * Namespace. 
     */
    protected $namespace = __NAMESPACE__;

    /**
     * Directory. 
     */
    protected $dir = __DIR__;

    /**
     * Models > Tables.
     */
    protected $models = ['Data' => 'trax_datastore_data'];
    
    /**
     * Register helpers.
     */
    protected $hasHelpers = true;
    
    /**
     * The service provider commands.
     */
    protected $commands = [
        /* Examples
        'Trax\DataStore\Console\DataCreateCommand',
        'Trax\DataStore\Console\DataListCommand',
        'Trax\DataStore\Console\DataDeleteCommand',
        'Trax\DataStore\Console\DataShowCommand',
        'Trax\DataStore\Console\DataUpdateCommand',
        'Trax\DataStore\Console\DataCountCommand',
        'Trax\DataStore\Console\DataClearCommand',
        */
    ];
    
    /**
     * The service provider global middlewares. May be overridden.
     * Must be declared manually in Lumen!
     */
    protected $globalMiddlewares = [CorsMiddleware::class];


    /**
     * Register services.
     */
    protected function registerServices()
    {
        $plugin = $this->plugin;
        $models = $this->models;
        $this->app->singleton('Trax\DataStore\MainDataStoreServices', function ($app) use ($plugin, $models) {
            return new MainDataStoreServices($app, $plugin, $models);
        });
    }

    /**
     * Register additional validation rules.
     */
    protected function registerValidationRules()
    {
        (new DataValidator($this->app))->register();
    }    
    
}
