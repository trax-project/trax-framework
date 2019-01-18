<?php

namespace Trax\Account\Http\Validations;

use Trax\DataStore\Http\Validations\RulesValidation;

trait AgreementValidation
{
    use RulesValidation;

    
    /**
     * Validation rules.
     */
    protected function rules($request, $id = null)
    {
        $rules = [
            'content' => 'required|string',
            'published' => 'nullable|boolean',
        ];
        return $rules;
    }

}
