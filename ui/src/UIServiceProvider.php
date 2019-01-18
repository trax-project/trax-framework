<?php

namespace Trax\UI;

use Illuminate\Support\ServiceProvider;

class UIServiceProvider extends ServiceProvider
{

    /**
     * List of language files to load for JS access.
     */
    protected $langFiles = ['trax-ui::common', 'trax-ui::datatables', 'trax-ui::form', 'trax-ui::lang', 'trax-ui::options'];

    /**
     * The service provider middlewares.
     */
    protected $middlewares = [
        'locale' => \Trax\UI\Http\Middleware\LocaleMiddleware::class,
    ];


    /**
     * Register the service provider.
     */
    public function register()
    {
        // UI disabled
        if (!config('trax.ui.enabled')) return;

        // Expose UI Services
        $this->app->singleton('Trax\UI\UIServices', function ($app) {
            return new UIServices($app);
        });
    }

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        // UI disabled
        if (!config('trax.ui.enabled')) return;

        /*
        // Console only boot
        if ($this->app->runningInConsole()) {

            // Publishes VueJS lang files
            $this->publishes([
                __DIR__.'/../publish/lang' => base_path('resources/lang'),
            ], 'trax-ui-lang');

            // Publishes App views
            $this->publishes([
                __DIR__.'/../publish/views' => base_path('resources/views'),
            ], 'trax-ui-views');

            // Publishes public assets
            $this->publishes([
                __DIR__.'/../publish/public' => base_path('public'),
            ], 'trax-ui-public');
        }
        */

        // Web boot & testing
        if (!$this->app->runningInConsole() || $this->app->runningUnitTests()) {
        
            // Load middlewares
            $router = $this->app->make('router');
            foreach($this->middlewares as $name => $class) {
                $router->aliasMiddleware($name, $class);
            }
        
            // Load routes
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    
            // Load translations
            $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'trax-ui');
    
            // Load views        
            $this->loadViewsFrom(__DIR__.'/../resources/views', 'trax-ui');
         
            // Load translations for JS side
            $ui = $this->app->make('Trax\UI\UIServices');
            $ui->registerLangFiles($this->langFiles, true);

            // Register nav data
            $ui->registerNav();

            // Register user data
            $accountServices = $this->app->make('Trax\Account\AccountServices');
            $ui->registerUser($accountServices);
        }
    }
}
