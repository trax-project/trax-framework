<?php

namespace Trax\Account\Validators;

use Auth;
use Hash;

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
            return Hash::check($value, Auth::user()->getAuthPassword());
        });
    }

}
