<?php

namespace Trax\Account\Http\Validations;

use Trax\DataStore\Http\Validations\RulesValidation;

trait EntityValidation
{
    use RulesValidation;


    /**
     * Validation rules.
     */
    protected function rules($request, $id = null)
    {
        $rules = [
            'uuid' => 'string|max:255|unique:' . traxConnection('trax-account', 'Entity') . '.trax_account_entities',
            'type_code' => 'nullable|option:TraxAccount.EntityTypes',
            'name' => 'string|min:1|max:255',
            'parent_id' => 'nullable|integer|exists:' . traxConnection('trax-account', 'Entity') . '.trax_account_entities,id',
        ];

        if (is_null($id)) {

            // Creation rules
            $rules['name'] .= '|required';
        } else {

            // Updating rules
            $rules['uuid'] .= ',uuid,' . $id . ',' . traxUnicityId('trax-account', 'Entity');
        }

        return $rules;
    }

}
