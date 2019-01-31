<?php

namespace Trax\Account\Http\Validations;

use Trax\DataStore\Http\Validations\RulesValidation;

trait GroupValidation
{
    use RulesValidation;

    
    /**
     * Validation rules.
     */
    protected function rules($request, $id = null)
    {
        $rules = [
            'uuid' => 'string|max:255|unique:' . traxConnection('trax-account', 'Group') . '.trax_account_groups',
            'status_code' => 'option:Trax.StandardStatus',
            'name' => 'string|min:1|max:255|unique:' . traxConnection('trax-account', 'Group') . '.trax_account_groups',
            'description' => 'nullable|string|max:4096',
        ];

        if (is_null($id)) {

            // Creation rules
            $rules['name'] = 'required|'. $rules['name'];
            $rules['status_code'] = 'required|'. $rules['status_code'];
        } else {

            // Updating rules
            $rules['uuid'] .= ',uuid,' . $id . ',' . traxUnicityId('trax-account', 'Group');
            $rules['name'] .= ',name,' . $id . ',' . traxUnicityId('trax-account', 'Group');
        }

        return $rules;
    }

}
