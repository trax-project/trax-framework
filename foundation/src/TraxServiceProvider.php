<?php

namespace Trax\Foundation;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Contracts\Debug\ExceptionHandler;

use Trax\Foundation\Exceptions\TraxExceptionHandler;

class TraxServiceProvider extends ServiceProvider
{
    /**
     * Plugin code. Must be overridden.
     */
    protected $plugin = 'trax-foundation';

    /**
     * Plugin dependencies. May be overridden.
     */
    protected $dependencies = [];

    /**
     * Namespace. Must be overridden.
     */
    protected $namespace = __NAMESPACE__;
    
    /**
     * Directory. Must be overridden.
     */
    protected $dir = __DIR__;

    /**
     * Register helpers. May be overridden.
     */
    protected $hasHelpers = false;

    /**
     * Register migrations. May be overridden.
     */
    protected $hasMigrations = false;

    /**
     * Register config file. May be overridden.
     */
    protected $hasConfig = false;

    /**
     * Register UI. May be overridden.
     */
    protected $hasUI = false;
    
    /**
     * The service provider commands. May be overridden.
     */
    protected $commands = [];
    
    /**
     * The service provider route middlewares. May be overridden.
     * Must be declared manually in Lumen!
     */
    protected $middlewares = [];
    
    /**
     * The service provider global middlewares. May be overridden.
     * Must be declared manually in Lumen!
     */
    protected $globalMiddlewares = [];
    
    /**
     * The service provider publications. May be overridden.
     */
    protected $publications = [
        /* Example
        'trax-config' => [
            'source' => 'publish/config',
            'destination' => 'config',
        ],
        */
    ];

    /**
     * The router object.
     */
    protected $router;

    /**
     * The kernel object.
     */
    protected $kernel;
    
            
    /**
     * Register the service provider.
     */
    public function register()
    {
        if (!$this->enabled()) return;

        // Needed services
        $this->router = $this->app->make('router');
        $this->kernel = $this->app->make(Kernel::class);

        // Registration
        $this->registerHelpers();
        $this->registerCommands();
        $this->registerConfiguration();
        $this->registerServices();
    }

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->registerPublications();

        if (!$this->enabled()) return;

        $this->registerMiddlewares();
        $this->registerMigrations();
        $this->registerValidationRules();
        $this->registerExceptionHandler();
        $this->registerUI();
        $this->registerPermissions();
        $this->registerEvents();
    }

    /**
     * Is the service enabled.
     */
    protected function enabled()
    {
        if ($this->plugin == 'trax-foundation') return true;
        $enabled = $this->hasConfig ? config($this->plugin.'.enabled') : true;
        if (!$enabled) return false;
        foreach ($this->dependencies as $plugin) {
            if (!config($plugin.'.enabled')) return false;
        }
        return true;
    }

    /**
     * Register commands.
     */
    protected function registerCommands()
    {
        $this->commands($this->commands);
    }
    
    /**
     * Register helpers.
     */
    protected function registerHelpers()
    {
        if ($this->hasHelpers || $this->plugin == 'trax-foundation') {
            require_once($this->dir . '/../helpers.php');
        }
    }
    
    /**
     * Register configuration files.
     */
    protected function registerConfiguration()
    {
        if (!traxRunningInLumen()) return;       // Laravel does it automatically
        
        if ($this->plugin == 'trax-foundation') {
            $this->app->configure('trax'); 
        } else if ($this->hasConfig) {
            $this->app->configure($this->plugin); 
        }
    }

    /**
     * Register services.
     */
    protected function registerServices()
    {
        if ($this->plugin != 'trax-foundation') return;
        $this->app->singleton('Trax\Foundation\TraxServices', function ($app) {
            return new TraxServices($app);
        });
    }

    /**
     * Register middlewares.
     */
    protected function registerMiddlewares()
    {
        if (traxRunningInLumen()) return;   // Routes must be registered manually in Lumen

        // Route middlewares
        foreach($this->middlewares as $name => $class) {
            $this->router->aliasMiddleware($name, $class);
        }

        // Global middlewares
        foreach($this->globalMiddlewares as $middlewareClass) {
            $this->kernel->prependMiddleware($middlewareClass);
        }
    }

    /**
     * Register migrations.
     */
    protected function registerMigrations()
    {
        if ($this->hasMigrations && $this->app->runningInConsole()) {
            $this->loadMigrationsFrom($this->dir.'/../database/migrations');
        }
    }
    
    /**
     * Register publications.
     */
    protected function registerPublications()
    {
        if (!$this->app->runningInConsole()) return;

        foreach($this->publications as $name => $info) {
            $this->publishes([
                $this->dir.'/../'.$info['source'] => base_path($info['destination']),
            ], $name);
        }
    }
    
    /**
     * Register additional validation rules.
     */
    protected function registerValidationRules()
    {
    }

    /**
     * Register a custom exception handler.
     */
    protected function registerExceptionHandler()
    {
        if ($this->plugin != 'trax-foundation') return;
        $this->app->bind(ExceptionHandler::class, TraxExceptionHandler::class);
    }

    /**
     * Register permissions.
     */
    protected function registerPermissions()
    {
        $words = explode('-', $this->plugin);
        array_shift($words);
        $words = implode('-', $words);
        $registrar = $this->namespace . '\\' . str_replace('-', '', ucwords($words, '-')) . 'Permissions';
        if (!class_exists($registrar)) return;
        $account = $this->app->make('Trax\Account\AccountServices');
        (new $registrar())->register($account);
    }

    /**
     * Register events.
     */
    protected function registerEvents()
    {
    }

    /**
     * Register UI.
     */
    protected function registerUI()
    {
        if (!config('trax.ui.enabled')) return;

        // Foundation only
        if ($this->plugin == 'trax-foundation') {
            $this->loadTranslationsFrom($this->dir . '/../resources/lang', $this->plugin);
            return;
        }

        if (!$this->hasUI) return;

        // UI routes
        $this->loadRoutesFrom($this->dir . '/../routes/web.php');     

        // Load translation files
        $this->loadTranslationsFrom($this->dir . '/../resources/lang', $this->plugin);

        // Load views        
        $this->loadViewsFrom($this->dir . '/../resources/views', $this->plugin);
         
        // Register UI services
        $this->registerUiServices();
    }

    /**
     * Register UI services.
     */
    protected function registerUiServices()
    {
        $words = explode('-', $this->plugin);
        array_shift($words);
        $words = implode('-', $words);
        $registrar = $this->namespace.'\\'.str_replace('-', '', ucwords($words, '-')) . 'UI';
        if (!class_exists($registrar)) return;
        $ui = $this->app->make('Trax\UI\UIServices');
        (new $registrar())->register($ui);
    }

}
