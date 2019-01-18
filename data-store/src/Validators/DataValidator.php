<?php

namespace Trax\DataStore\Validators;

class DataValidator
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
        $this->registerOptionRule();
        $this->registerIsoDateRule();
        $this->registerIsoDurationRule();
        $this->registerIsoLangRule();
        $this->registerContentTypeRule();
        $this->registerUuidRule();
        $this->registerBooleanFlexRule();
        $this->registerNumericStrictRule();
        $this->registerIntegerStrictRule();
        $this->registerForbiddenRule();
        $this->registerForbiddenWithRule();
    }

    /**
     * Register Option rule.
     */
    protected function registerOptionRule()
    {
        $this->validator->extend('option', function ($attribute, $value, $parameters, $validator) {
            if (empty($parameters)) return false;
            $options = traxOptions($parameters[0]);
            try {
                $options->find($value);
            } catch(\Exception $e) {
                return false;
            }
            return true;
        });
    }

    /**
     * Register ISO 8601 Timestamp rule.
     * From http://www.pelagodesign.com/blog/2009/05/20/iso-8601-date-validation-that-doesnt-suck/
     */
    protected function registerIsoDateRule()
    {
        $this->validator->extend('iso_date', function ($attribute, $value, $parameters, $validator) {
            if (!is_string($value)) return false;
            return (bool)preg_match('/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|((?!-0{2}(:0{2})?)([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?)?$/', $value);
        });
    }

    /**
     * Register ISO 8601 Duration rule.
     */
    protected function registerIsoDurationRule()
    {
        $this->validator->extend('iso_duration', function ($attribute, $value, $parameters, $validator) {
            if (!is_string($value)) return false;
            $classic = (bool) preg_match('/^P((\d+([\.,]\d+)?Y)?(\d+([\.,]\d+)?M)?(\d+([\.,]\d+)?D)?)?(T(\d+([\.,]\d+)?H)?(\d+([\.,]\d+)?M)?(\d+([\.,]\d+)?S)?)?$/i', $value);
            $weeks = (bool) preg_match('/^P\d+W$/i', $value);
            return $classic || $weeks;
        });
    }

    /**
     * Register ISO Lang rule.
     */
    protected function registerIsoLangRule()
    {
        $this->validator->extend('iso_lang', function ($attribute, $value, $parameters, $validator) {
            if (!is_string($value)) return false;
            return (bool) preg_match('/^(([a-zA-Z]{2,8}((-[a-zA-Z]{3}){0,3})(-[a-zA-Z]{4})?((-[a-zA-Z]{2})|(-\d{3}))?(-[a-zA-Z\d]{4,8})*(-[a-zA-Z\d](-[a-zA-Z\d]{1,8})+)*)|x(-[a-zA-Z\d]{1,8})+|en-GB-oed|i-ami|i-bnn|i-default|i-enochian|i-hak|i-klingon|i-lux|i-mingo|i-navajo|i-pwn|i-tao|i-tsu|i-tay|sgn-BE-FR|sgn-BE-NL|sgn-CH-DE)$/iu', $value);
        });
    }

    /**
     * Register HTTP Content Type rule.
     */
    protected function registerContentTypeRule()
    {
        $this->validator->extend('content_type', function ($attribute, $value, $parameters, $validator) {
            if (!is_string($value)) return false;
            return (bool) preg_match('#^(application|audio|example|image|message|model|multipart|text|video)(/[-\w\+]+)(;\s*[-\w]+\=[-\w]+)*;?$#', $value);
        });
    }

    /**
     * Register UUID rule.
     */
    protected function registerUuidRule()
    {
        $this->validator->extend('uuid', function ($attribute, $value, $parameters, $validator) {
            if (!is_string($value)) return false;
            return (bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $value);
        });
    }

    /**
     * Register Boolean Flex rule ("true" & "false" allowed).
     */
    protected function registerBooleanFlexRule()
    {
        $this->validator->extend('boolean_flex', function ($attribute, $value, $parameters, $validator) {
            return ($this->validate($value, 'boolean') || $value == 'true' || $value == 'false');
        });
    }

    /**
     * Register Numeric Strict rule (string not allowed).
     */
    protected function registerNumericStrictRule()
    {
        $this->validator->extend('numeric_strict', function ($attribute, $value, $parameters, $validator) {
            return (!is_string($value) && $this->validate($value, 'numeric'));
        });
    }

    /**
     * Register Integer Strict rule (string not allowed).
     */
    protected function registerIntegerStrictRule()
    {
        $this->validator->extend('integer_strict', function ($attribute, $value, $parameters, $validator) {
            return (!is_string($value) && $this->validate($value, 'integer'));
        });
    }

    /**
     * Register Forbidden rule.
     */
    protected function registerForbiddenRule()
    {
        $this->validator->extend('forbidden', function ($attribute, $value, $parameters, $validator) {
            return (is_null($value));
        });
    }

    /**
     * Register Forbidden_With rule.
     */
    protected function registerForbiddenWithRule()
    {
        $this->validator->extend('forbidden_with', function ($attribute, $value, $parameters, $validator) {
            $attributes = array_keys($validator->attributes());
            return (is_null($value) || empty(array_intersect($attributes, $parameters)));
        });
    }

    /**
     * Validate something given validation rules.
     */
    protected function validate($data, $rules) {
        $validator = app('validator')->make(['data' => $data], ['data' => $rules]);
        return $validator->passes();
    }

}
