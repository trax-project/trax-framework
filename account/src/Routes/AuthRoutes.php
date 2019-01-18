<?php

namespace Trax\Account\Routes;

class AuthRoutes
{
    /**
     * Router.
     */
    protected $router;
    

    /**
     * Register the routes.
     */
    public function register($router)
    {
        $this->router = $router;

        // No Auth route with Lumen
        if (traxRunningInLumen()) return;

        // No auth config
        if (!config('trax-account.auth.enabled')) return;
        
        
        // Configurable controllers
        
        $this->router->middleware(['web', 'locale'])->group(function () {

            // Authenticated home
            $this->router->get('/', config('trax-account.controllers.home') . '@root')->name('root');
            $this->router->get('home', config('trax-account.controllers.home') . '@home')->middleware('auth')->name('home');
        });

        // Trax controllers
        
        $this->router->middleware(['web', 'locale'])->namespace('Trax\Account\Http\Controllers\Auth')->group(function () {
        
            // Authentication Routes...
            $this->router->get('login', 'LoginController@showLoginForm')->name('login');
            $this->router->post('login', 'LoginController@login');
            $this->router->get('logout', 'LoginController@logout')->name('logout');
            $this->router->post('logout', 'LoginController@logout');
    
            // Registration Routes...
            if (config('trax-account.auth.registration')) {
                $this->router->get('register', 'RegisterController@showRegistrationForm')->name('register');
                $this->router->post('register', 'RegisterController@register');
            }
            
            // Password Reset Routes...
            if (config('trax-account.auth.password-reset')) {
                $this->router->get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->middleware('guest')->name('password.request');
                $this->router->post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->middleware('guest')->name('password.email');
                $this->router->get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
                $this->router->post('password/reset', 'ResetPasswordController@reset');

                if (config('trax-account.auth.invitation')) {
                    $this->router->post('invitation/email', 'InvitationController@sendInvitationEmail')->middleware('auth')->name('invitation.email');
                }
            }
        });
    }
    

}
