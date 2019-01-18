<?php

namespace Trax\DataStore\Http\Controllers;

use Illuminate\Http\Request;

use Trax\DataStore\Http\Validations\DataValidation;
use Trax\DataStore\Http\Guards\DataGuard;
use Trax\DataStore\Http\Hooks\DataHook;
use Trax\Account\DataStoreAuthorizer;

class DataWsController extends DataStoreController
{
    use DataStoreAuthorizer, DataGuard, DataHook, DataValidation;

    /**
     * Delete exception message key.
     */
    protected $deleteExceptionMessageKey;

    
    /**
     * Get data entries.
     */
    public function get(Request $request)
    {
        $search = $this->allowsRead($request, null);     
        $this->guardGetRequest($request);
        $this->validateGetRequest($request);
        $args = $request->query();
        $this->filter($args);
        $this->search($args, $search);
        $options = array_merge($this->options, ['count' => true]);
        $this->withRelations($request, $options);
        $res = $this->store->get($args, $options);
        return response()->json($res);
    }
    
    /**
     * Store a data entry and return its ID.
     */
    public function store(Request $request)
    {
        $this->allowsCreate($request);
        $this->guardStoreRequest($request);
        $this->validateStoreRequest($request);
        $data = $this->validateStoreContent($request);
        $res = $this->store->store($data, $this->options);
        if (!is_string($res) && !is_numeric($res)) $this->finalizeData($res);
        $this->hookDataStored($request, $data, $res);
        return response()->json($res);
    }
    
    /**
     * Update a data entry.
     */
    public function update(Request $request, $id = null)
    {
        $this->allowsUpdate($request, $id);
        $this->guardUpdateRequest($request, $id);
        $this->validateUpdateRequest($request, $id);
        $data = $this->validateUpdateContent($request, $id);
        $model = $this->store->find($id);
        $res = $this->store->update($id, $data, $this->options);
        if (!is_string($res) && !is_numeric($res)) $this->finalizeData($res);
        $this->hookDataUpdated($request, $model, $data, $res);
        return response()->json($res);
    }
    
    /**
     * Find a data entry by its id.
     */
    public function find(Request $request, $id = null)
    {
        $this->allowsRead($request, $id);
        $this->guardFindRequest($request, $id);
        $this->validateFindRequest($request, $id);
        $this->withRelations($request, $this->options);
        $res = $this->store->find($id, $this->options);
        $this->finalizeData($res);
        return response()->json($res);
    }
    
    /**
     * Find a data entry.
     */
    public function findBy(Request $request)
    {
        $this->allowsRead($request);    // ID null, so returns search criteria, but never throw exception
                                        // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        $this->guardFindByRequest($request);
        $this->validateFindByRequest($request);
        $query = $request->query();
        foreach($query as $prop => $val) break;
        $prop = str_replace('_', '.', $prop);
        $this->withRelations($request, $this->options);
        $res = $this->store->findBy($prop, $val, $this->options);
        $this->finalizeData($res);
        return response()->json($res);
    }
    
    /**
     * Delete a data entry.
     */
    public function delete(Request $request, $id = null)
    {
        $this->allowsDelete($request, $id);
        $this->guardDeleteRequest($request, $id);
        $this->validateDeleteRequest($request, $id);
        try {
            $res = $this->store->delete($id);
        } catch(\Exception $e) {
            if (isset($this->deleteExceptionMessageKey)) {
                return response(__($this->deleteExceptionMessageKey), 404);
            } else {
                abort(404);
            }
        }
        return response('', 204);
    }
    
    /**
     * Count data entries.
     */
    public function count(Request $request)
    {
        $this->allowsRead($request);    // ID null, so returns search criteria, but never throw exception
                                        // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        $this->guardCountRequest($request);
        $this->validateCountRequest($request);
        $res = $this->store->count();
        return response()->json($res);
    }
    
    /**
     * Clear data entries.
     */
    public function clear(Request $request)
    {
        $this->allowsDelete($request);
        $this->guardClearRequest($request);
        $this->validateClearRequest($request);
        $res = $this->store->clear();
        return response('', 204);
    }

    /**
     * Filtering function provided by the controller.
     */
    protected function filter(&$args)
    {
        $filters = [];
        if (isset($args['filters'])) $filters = $args['filters'];
        $filters = array_merge($filters, $this->filters);
        if (!empty($filters)) $args['filters'] = $filters;
    }

    /**
     * Searching function provided by the controller.
     */
    protected function search(&$args, $searchMore = [])
    {
        $search = [];
        if (isset($args['search'])) $search = $args['search'];
        if (is_array($searchMore)) $search = array_merge($search, $searchMore);
        if (!empty($search)) $args['search'] = $search;
    }

    /**
     * Extracting 'with' option for relations.
     */
    protected function withRelations($request, &$options)
    {
        if ($request->has('with')) $options['with'] = $request->input('with');
    }

    /**
     * Finalize outpout data.
     */
    protected function finalizeData(&$data)
    {
    }

}
