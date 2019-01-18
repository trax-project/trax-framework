<?php

namespace Trax\Account\Http\Validations;

use Trax\DataStore\Http\Validations\RulesValidation;

trait BasicClientValidation
{
    use RulesValidation;

    
    /**
     * Validation rules.
     */
    protected function rules($request, $id = null)
    {
        $rules = [
            'name' => 'string|min:1|max:255',
            'username' => 'string|min:1|max:255|unique:'.traxConnection('trax-account', 'BasicClient'). '.trax_account_basic_clients',
            'password' => 'string|min:6',
        ];

        if (is_null($id)) {

            // Creation rules
            $rules['name'] .= '|required';
            $rules['username'] .= '|required';
            $rules['password'] .= '|required';
        } else {

            // Update specific rules
            $rules['username'] .= ',username,'.$id.','.traxUnicityId('trax-account', 'BasicClient');
        }

        return $rules;
    }

}
