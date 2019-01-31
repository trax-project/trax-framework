<?php

namespace Trax\Account\Http\Validations;

use Trax\DataStore\Http\Validations\RulesValidation;

trait UserValidation
{
    use RulesValidation;

    /**
     * Validation rules.
     */
    protected function rules($request, $id = null)
    {
        $rules = [

            // Identification
            'source_code' => 'option:TraxAccount.UserSources',
            'uuid' => 'string|max:255|unique:' . traxConnection('trax-account', 'User') . '.trax_account_users',
            'username' => 'string|max:255|unique:' . traxConnection('trax-account', 'User') . '.trax_account_users',
            'email' => 'required|string|email|max:255|unique:' . traxConnection('trax-account', 'User') . '.trax_account_users',
            
            // Metadata
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'lang' => 'string|option:TraxUI.Languages',

            // Access
            'admin' => 'boolean',
            'active' => 'boolean',
            'password' => 'string|min:6|confirmed',
            'current_password' => 'string|min:6',

            // Rights
            'entity_type_code' => 'nullable|string|option:TraxAccount.EntityTypes',
            'organization_id' => 'nullable|integer|exists:' . traxConnection('trax-account', 'Entity') . '.trax_account_entities,id',
            'entity_id' => 'nullable|integer|exists:' . traxConnection('trax-account', 'Entity') . '.trax_account_entities,id',
            'rights_level_code' => 'string|option:TraxAccount.RightsLevels',
            'role_id' => 'nullable|integer|exists:' . traxConnection('trax-account', 'Role') . '.trax_account_roles,id',
            'user_function_code' => 'nullable|string|option:TraxAccount.UserFunctions',

        ];

        // Password change
        if ($request->has('password')) {
            $rules['current_password'] .= '|required|passcheck';
        } 

        // Updating rules
        if (!is_null($id)) {
            $rules['uuid'] .= ',uuid,' . $id . ',' . traxUnicityId('trax-account', 'User');
            $rules['username'] .= ',username,' . $id . ',' . traxUnicityId('trax-account', 'User');
            $rules['email'] .= ',email,' . $id . ',' . traxUnicityId('trax-account', 'User');
        }

        // Required conditionnally
        if (config('trax-account.auth.username')) {
            $rules['username'] .= '|required';
        }

        return $rules;
    }

}
