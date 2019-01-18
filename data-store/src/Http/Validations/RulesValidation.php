<?php

namespace Trax\DataStore\Http\Validations;

use Illuminate\Http\Request;

trait RulesValidation
{

    /**
     * Validation rules.
     */
    protected function rules($request, $id = null)
    {
        return [];
    }

    /**
     * Validate store request.
     */
    protected function validateStoreRequest(Request $request)
    {
        $this->rulesValidateStoreRequest($request);
    }

    /**
     * Validate update request.
     */
    protected function validateUpdateRequest(Request $request, $id = null)
    {
        $this->rulesValidateUpdateRequest($request, $id);
    }

    /**
     * Validate store request: direct access.
     */
    protected function rulesValidateStoreRequest(Request $request)
    {
        parent::validateStoreRequest($request);
        $rules = $this->rules($request);
        $this->validateRequest($request, [
            'rules' => $rules,
        ]);
    }

    /**
     * Validate update request: direct access.
     */
    protected function rulesValidateUpdateRequest(Request $request, $id = null)
    {
        parent::validateUpdateRequest($request, $id);
        $rules = $this->rules($request, $id);
        $this->validateRequest($request, [
            'rules' => $rules,
        ]);
    }

}
