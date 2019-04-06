<?php

namespace Trax\Account\Validators;

use Auth;
use Hash;
use Trax\Account\Ldap\LdapConnector;

class AccountValidator
{
    /**
     * App.
     */
    protected $app;
    
    /**
     * Validator.
     */
    protected $validator;
    
    
    /**
     * Construct.
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->validator = $app->make('validator');
    }
    
    /**
     * Register the rules.
     */
    public function register()
    {
        $this->registerPasswordCheckRule();
    }

    /**
     * Register password check rule.
     */
    protected function registerPasswordCheckRule()
    {
        $this->validator->extend('passcheck', function ($attribute, $value, $parameters, $validator) {

            $user = Auth::user();
            if ($user->source_code == 'ldap') {
                return (new LdapConnector)->validateCredentials($user, ['password' => $value]);
            } else {
                return Hash::check($value, Auth::user()->getAuthPassword());
            }
        });
    }

}
