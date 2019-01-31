<?php

namespace Trax\DataStore\Http\Validations;

use Illuminate\Http\Request;

use Trax\DataStore\Exceptions\RequestException;

trait DataValidation
{

    /**
     * Validate get request.
     */
    protected function validateGetRequest(Request $request)
    {
    }
    
    /**
     * Validate store request.
     */
    protected function validateStoreRequest(Request $request)
    {
        $this->checkJsonRequest($request);
    }
    
    /**
     * Validate store request content.
     */
    protected function validateStoreContent(Request $request)
    {
        return $this->checkJsonContent($request);
    }
    
    /**
     * Validate update request.
     */
    protected function validateUpdateRequest(Request $request, $id = null)
    {
        $this->checkJsonRequest($request);
    }
    
    /**
     * Validate update request request.
     */
    protected function validateUpdateContent(Request $request, $id = null)
    {
        return $this->checkJsonContent($request);
    }
    
    /**
     * Validate find request.
     */
    protected function validateFindRequest(Request $request, $id = null)
    {
    }
    
    /**
     * Validate findBy request.
     */
    protected function validateFindByRequest(Request $request)
    {
        $query = $request->query();
        if (count($query) != 1) throw new RequestException();
    }
    
    /**
     * Validate delete request.
     */
    protected function validateDeleteRequest(Request $request, $id = null)
    {
    }
    
    /**
     * Validate count request.
     */
    protected function validateCountRequest(Request $request)
    {
    }
    
    /**
     * Validate clear request.
     */
    protected function validateClearRequest(Request $request)
    {
    }
    
    /**
     * Validate request.
     */
    protected function validateRequest(Request $request, $behavior)
    {
        $rules = $behavior['rules'];
        $customExceptions = isset($behavior['errors']) ? $behavior['errors'] : null;
        $validator = app('validator')->make($request->all(), $rules);
        
        // AJAX requests
        if ($this->ajax($request)) return $validator->validate();

        // WS requests
        $this->checkValidationErrors($validator, $customExceptions);
                    
        // Check that all params are known
        if (isset($behavior['limited_to']))
            $this->checkValidationLimitedTo($request, $behavior['limited_to'], $customExceptions);
    }
    
    /**
     * Check validation errors.
     */
    protected function checkValidationErrors($validator, $customExceptions = null)
    {
        if ($validator->fails()) {
            $failed = $validator->failed();
            foreach($failed as $prop => $errors) {
                foreach($errors as $rule => $error) {
                    if (!isset($customExceptions)) {
                        throw new RequestException('Invalid "'.$prop.'" param.');
                    } else if (!is_array($customExceptions)) {
                        throw $customExceptions;
                    } else if (isset($customExceptions[$prop]) && !is_array($customExceptions[$prop])) {
                        throw $customExceptions[$prop];
                    } else if (isset($customExceptions[$prop][$rule])) {
                        throw $customExceptions[$prop][$rule];
                    } else {
                        throw new RequestException('Invalid "'.$prop.'" param.');
                    }
                }
            }
        }
    }
    
    /**
     * Check all params are known.
     */
    protected function checkValidationLimitedTo($request, $limitedTo, $customExceptions = null)
    {
        // Get inputs
        if ($request->isJson()) $inputs = $request->query();
        else $inputs = $request->all();
        $inputs = array_keys($inputs);

        // Check them
        foreach($inputs as $input) {
            if (!in_array($input, $limitedTo)) {
                if (isset($customExceptions) && !is_array($customExceptions)) {
                    throw $customExceptions;
                } else {
                    throw new RequestException('Unkown "'.$input.'" param.');
                }
            }
        }
    }
    
    /**
     * Check JSON request.
     */
    protected function checkJsonRequest(Request $request)
    {
        if (
            !$request->hasHeader('Content-Type') 
            || strpos($request->header('Content-Type'), 'application/json') === false
        ) 
            throw new RequestException();
    }

    /**
     * Check JSON content.
     */
    protected function checkJsonContent(Request $request)
    {
        if (!json_decode($request->getContent())) 
            throw new RequestException();
        return $request->json()->all();
    }

    /**
     * Is it an Ajax request?
     */
    protected function ajax(Request $request)
    {
        return (strpos($request->url(), 'ajax') !== false);
    }

}
