<?php

namespace Trax\Account\Routes;

use Trax\DataStore\Routes\DataRoutes;

class UserRoutes extends DataRoutes
{
    /**
     * The data model.
     */
    protected $model = 'User';

    /**
     * Only these API methods. May be overridden.
     */
    protected $only = array('store', 'get', 'find', 'update', 'delete', 'register');

    /**
     * Left relations.
     */
    protected $leftRelations = [
        'agreements' => 'AgreementUser'
    ];


    /**
     * Register more routes.
     */
    public function register($router)
    {
        parent::register($router);

        if (traxRunningInLumen()) return;

        // Additional routes
        $this->router->middleware(['web', 'locale'])->namespace('Trax\Account\Http\Controllers')->group(function () {

            // Check password
            $this->router->post('trax/ajax/account/users/passcheck', 'UserController@checkPassword');

            // Set preference
            $this->router->post('trax/ajax/account/users/preferences', 'UserController@setPreferences');

            // File posting routes...
            if (config('trax-account.files.enabled')) {
                $this->router->post('trax/ajax/account/users/{id}/picture', 'UserController@postPicture');
            }
        });
    }

}
