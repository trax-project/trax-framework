<?php

namespace Trax\DataStore\Routes;

class DataRoutes
{
    /**
     * The data model. Must be overridden.
     */
    protected $model = 'Data';
    
    /**
     * Route name. May be overridden.
     */
    protected $routeName;
    
    /**
     * Only these API methods. May be overridden.
     */
    protected $only = array();
    
    /**
     * Except these API methods. May be overridden.
     */
    protected $except = array();
    
    /**
     * Middlewares which are added to all routes. May be overridden.
     */
    protected $middlewares = array();

    /**
     * Plugin name.
     */
    protected $plugin;

    /**
     * Plugin vendor.
     */
    protected $vendor;

    /**
     * Namespace.
     */
    protected $namespace;
    
    /**
     * Controller prefix.
     */
    protected $controllerPrefix = '';
    
    /**
     * Router.
     */
    protected $router;
    
    /**
     * Config.
     */
    protected $config;


    /**
     * Store methods. 
     */
    protected $methods = array(
        'count' => ['http' => 'get', 'route' => '/count'],      // Keep it at the begining
        'findby' => ['http' => 'get', 'route' => '/findby'],    // Keep it at the begining
        'get' => ['http' => 'get', 'route' => ''],
        'store' => ['http' => 'post', 'route' => ''],
        'find' => ['http' => 'get', 'route' => '/{id}'],
        'duplicate' => ['http' => 'post', 'route' => '/{id}/duplicate'],
        'update' => ['http' => 'put', 'route' => '/{id}'],
        'delete' => ['http' => 'delete', 'route' => '/{id}'],
        'clear' => ['http' => 'delete', 'route' => ''],
    );

    /**
     * Relation methods. 
     */
    protected $leftRelationMethods = array(
        'members' => ['http' => 'get', 'route' => ''],
        'register' => ['http' => 'post', 'route' => '/register'],
        'unregister' => ['http' => 'post', 'route' => '/unregister'],
        'toggle' => ['http' => 'post', 'route' => '/toggle'],
    );

    /**
     * Left relations.
     */
    protected $leftRelations = [];

    
    /**
     * Construct.
     */
    public function __construct($plugin, $namespace, $config)
    {
        $this->plugin = $plugin;
        $words = explode('-', $this->plugin);
        $this->vendor = array_shift($words);
        $this->namespace = $namespace;
        $this->config = $config;
        if (!isset($this->routeName)) $this->routeName = traxRouteName($this->plugin, $this->model);
    }
    
    /**
     * Register the routes.
     */
    public function register($router)
    {
        $this->router = $router;
        if (isset($this->config['ajax']) && $this->config['ajax'] && !traxRunningInLumen()) $this->registerAjaxApi();
        if (isset($this->config['ws']) && $this->config['ws']) $this->registerWsApi();
    }
    
    /**
     * Register AJAX routes.
     */
    protected function registerAjaxApi()
    {
        $controller = null;
        $routerStart = $this->router->middleware(['web', 'auth', 'locale']);
        if (isset($this->config['controller'])) {
            $controller = $this->config['controller'];
        } else {
            $routerStart = $routerStart->namespace($this->namespace . '\Http\Controllers');
        }
        $routerStart->group(function () use ($controller) {

            // Register store routes
            foreach ($this->methods as $method => $settings) {
                if ($this->has($method)) $this->registerAjaxMethod($method, $settings['http'], $settings['route'], $controller);
            } 

        });
        if (isset($this->config['controller'])) {
            $routerStart = $routerStart->namespace($this->namespace . '\Http\Controllers');
        }
        $routerStart->group(function () {

            // Register left relation routes
            foreach ($this->leftRelations as $target => $model) {
                foreach ($this->leftRelationMethods as $method => $settings) {
                    $route = '/{id}/' . $target . $settings['route'];
                    if ($this->has($method)) $this->registerAjaxMethod($method, $settings['http'], $route, null, $model, $target);
                }
            }
        });
    }

    /**
     * Register WS routes.
     */
    protected function registerWsApi()
    {
        if (traxRunningInLumen()) $this->registerWsApiForLumen();
        else $this->registerWsApiForLaravel();
    }

    /**
     * Register WS routes for Laravel.
     */
    protected function registerWsApiForLaravel()
    {
        $controller = null;
        $routerStart = $this->router->middleware(['auth.basic.once']);
        if (isset($this->config['controller'])) {
            $controller = $this->config['controller'];
        } else {
            $routerStart = $routerStart->namespace($this->namespace . '\Http\Controllers');
        }
        $routerStart->group(function () use($controller) {
            foreach ($this->methods as $method => $settings) {
                if ($this->has($method)) $this->registerWsMethodForLaravel($method, $settings['http'], $settings['route'], $controller);
            } 
        });
    }

    /**
     * Register WS routes for Lumen.
     */
    protected function registerWsApiForLumen()
    {
        $controller = null;
        if (isset($this->config['controller'])) {
            $controller = $this->config['controller'];
            $groupSettings = ['middleware' => 'auth.basic.once'];
        } else {
            $groupSettings = ['middleware' => 'auth.basic.once', 'namespace' => $this->namespace . '\Http\Controllers'];
        }
        $this->router->group($groupSettings, function () use($controller) {
            foreach ($this->methods as $method => $settings) {
                if ($this->has($method)) $this->registerWsMethodForLumen($method, $settings['http'], $settings['route'], $controller);
            } 
        });
    }

    /**
     * Register a WS method for Laravel.
     */
    protected function registerWsMethodForLaravel($method, $http, $route = '', $controller = null)
    {
        $routeBase = str_replace('.', '/', $this->routeName);
        $uri = $this->vendor.'/ws/'.$routeBase.$route;
        if (!isset($controller)) $controller = $this->controllerPrefix.$this->model.'Controller';
        $this->router->$http($uri, $controller.'@'.$method)
            ->middleware($this->middlewares)
            ->name($this->vendor . '.ws.'.$this->routeName.'.'.$method);
    }
        
    /**
     * Register a WS method for Lumen.
     */
    protected function registerWsMethodForLumen($method, $http, $route = '', $controller = null)
    {
        $routeBase = str_replace('.', '/', $this->routeName);
        $uri = $this->vendor . '/ws/'.$routeBase.$route;
        if (!isset($controller)) $controller = $this->controllerPrefix.$this->model.'Controller';
        $this->router->$http($uri, [
            'as' => $this->vendor . '.ws.'.$this->routeName.'.'.$method,
            'uses' => $controller.'@'.$method,
            'middleware' => $this->middlewares,
        ]);
    }
    
    /**
     * Register WS routes.
     */
    protected function registerAjaxMethod($method, $http, $route = '', $controller = null, $model = null, $target = null)
    {
        $routeBase = str_replace('.', '/', $this->routeName);
        $uri = $this->vendor . '/ajax/'.$routeBase.$route;
        if (!isset($model)) $model = $this->model;
        if (!isset($controller)) $controller = $this->controllerPrefix. $model .'Controller';
        $methodName = isset($target) ? $target.'.'. $method : $method;
        $this->router->$http($uri, $controller.'@'.$method)
            ->middleware($this->middlewares)
            ->name($this->vendor . '.ajax.'.$this->routeName.'.'. $methodName);
    }

    /**
     * Tell if the API supports a method.
     */
    protected function has($method)
    {
        return (!in_array($method, $this->except) && (empty($this->only) || in_array($method, $this->only)));
    }
    

}
