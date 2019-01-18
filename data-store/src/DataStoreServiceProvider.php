<?php

namespace Trax\DataStore;

use Trax\Foundation\TraxServiceProvider;

class DataStoreServiceProvider extends TraxServiceProvider
{
    /**
     * Register migrations. 
     */
    protected $hasMigrations = true;

    /**
     * Register config file. 
     */
    protected $hasConfig = true;

    /**
     * Models > Tables. Must be overridden.
     */
    protected $models = [];   // Ex. ['Data' => 'data'];
        
            
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        if (!$this->enabled()) return;
        parent::boot();
        $this->registerRoutes();        
    }

    /**
     * Register routes.
     */
    protected function registerRoutes($models = null)
    {
        if (!isset($models)) $models = $this->models;
        foreach ($models as $model => $table) {
            $config = config($this->plugin.'.stores.'.$model);
            if ((isset($config['ws']) && $config['ws']) || (isset($config['ajax']) && $config['ajax'])) {
                $routesClass = $this->namespace.'\Routes\\'.$model.'Routes';
                (new $routesClass($this->plugin, $this->namespace, $config))->register($this->router);
            }
        }
    }
    
}
