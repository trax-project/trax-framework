<?php

namespace Trax\DataStore;

abstract class DataStoreServices
{
    /**
     * The application instance.
     */
    protected $app;

    /**
     * Plugin code.
     */
    protected $plugin;

    /**
     * Namespace. Must be overridden.
     */
    protected $namespace = __NAMESPACE__;
    
    /**
     * Registered models.
     */
    protected $models = array();
    
    /**
     * The data stores.
     */
    protected $stores = array();

    
    /**
     * Create a new services instance.
     */
    public function __construct($app, $plugin, $models)
    {
        $this->app = $app;
        $this->plugin = $plugin;
        $this->registerModels($models, $this->namespace);
        $this->init();
    }

    /**
     * Init.
     */
    protected function init()
    {
    }

    /**
     * Register stores.
     */
    protected function registerModels($models, $namespace)
    {
        foreach ($models as $model => $table) {
            $this->models[$model] = ['table' => $table, 'namespace' => $namespace];
        }
    }

    /**
     * Get a data store.
     */
    protected function store($modelName = 'Data', $prefix = '') {
        if (isset($stores[$modelName])) return $stores[$modelName];
        $storeClass = $this->storeClass($modelName, $prefix);
        $store = new $storeClass($this->app, traxConnection($this->plugin, $modelName), $this->dbTable($modelName), $this->modelClass($modelName));
        $stores[$modelName] = $store;
        return $store;
    }

    /**
     * Get the store class given a model name.
     */
    protected function storeClass($modelName, $prefix = '') {
        return $this->models[$modelName]['namespace'].'\Stores\\'.$prefix.$modelName.'Store';
    }

    /**
     * Get the model class given a model name.
     */
    protected function modelClass($modelName) {
        return $this->models[$modelName]['namespace'].'\Models\\'.$modelName;
    }

    /**
     * Get the table given a model name.
     */
    protected function dbTable($modelName) {
        return $this->models[$modelName]['table'];
    }


}
