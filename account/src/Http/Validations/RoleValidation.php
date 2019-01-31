<?php

namespace Trax\Account\Http\Validations;

use Trax\DataStore\Http\Validations\RulesValidation;

trait RoleValidation
{
    use RulesValidation;

    
    /**
     * Validation rules.
     */
    protected function rules($request, $id = null)
    {
        $rules = [
            'name' => 'string|min:1|max:255|unique:' . traxConnection('trax-account', 'Role') . '.trax_account_roles',
            'description' => 'nullable|string|max:4096',
        ];

        if (is_null($id)) {

            // Creation rules
            $rules['name'] .= '|required';
        } else {

            // Updating rules
            $rules['name'] .= ',name,' . $id . ',' . traxUnicityId('trax-account', 'Role');
        }

        return $rules;
    }

}
