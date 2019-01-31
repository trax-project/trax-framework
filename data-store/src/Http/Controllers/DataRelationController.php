<?php

namespace Trax\DataStore\Http\Controllers;

use Illuminate\Http\Request;

trait DataRelationController
{

    /**
     * Left store.
     */
    protected $leftStore;

    /**
     * Right store.
     */
    protected $rightStore;

    /**
     * Left model.
     */
    protected $leftModel;

    /**
     * Right model.
     */
    protected $rightModel;


    /**
     * Create a new controller instance.
     */
    protected function initStores($services)
    {
        $this->services = $services;
        $this->leftStore = $this->services->{$this->leftStoreName}();
        $this->rightStore = $this->services->{$this->rightStoreName}();
        $this->leftModel = $this->leftStore->model();
        $this->rightModel = $this->rightStore->model();
    }

    /**
     * Get members.
     */
    public function members(Request $request, $id)
    {
        $this->store = $this->rightStore;
        $start = $this->leftModel::findOrFail($id)->{$this->rightStoreName}()->select('id');

        // Pivot filter
        if ($request->has('pivot') && !empty($request->input('pivot'))) {
            foreach ($request->input('pivot') as $prop => $val) {
                $start = $start->wherePivot($prop, $val);
            }
        }
        
        // Ids result
        $ids = $start->get()->pluck('id')->toArray();
        if ($request->has('format') && $request->input('format') == 'id') {
            return $ids;
        }

        // Models result: works only when the right store implements the ids filter.
        $this->filters['ids'] = $ids;
        return parent::get($request);
    }

    /**
     * Register a new membership.
     */
    public function register(Request $request, $id)
    {
        $this->authorizerModel($this->leftModel)->allowsUpdate($request, $id);
        $rightItem = $this->rightModel::findOrFail($request->input('member_id'));
        $leftItem = $this->leftModel::findOrFail($id);
        $this->guardRegisterRequest($request, $leftItem, $rightItem);
        $pivot = [];
        if (isset($this->pivotCreatedAt) && $this->pivotCreatedAt) $pivot['created_at'] = traxNow();
        if (isset($this->pivotUpdatedAt) && $this->pivotUpdatedAt) $pivot['updated_at'] = traxNow();
        $leftItem->{$this->rightStoreName}()->attach($rightItem->id, $pivot);
        return response('No Content', 204);
    }

    /**
     * Unregister a membership.
     */
    public function unregister(Request $request, $id)
    {
        $this->authorizerModel($this->leftModel)->allowsUpdate($request, $id);
        $rightItem = $this->rightModel::findOrFail($request->input('member_id'));
        $leftItem = $this->leftModel::findOrFail($id);
        $this->guardUnregisterRequest($request, $leftItem, $rightItem);
        $pivot = [];
        if (isset($this->pivotCreatedAt) && $this->pivotCreatedAt) $pivot['created_at'] = traxNow();
        if (isset($this->pivotUpdatedAt) && $this->pivotUpdatedAt) $pivot['updated_at'] = traxNow();
        $leftItem->{$this->rightStoreName}()->detach($rightItem->id, $pivot);
        return response('No Content', 204);
    }

    /**
     * Toggle a membership.
     */
    public function toggle(Request $request, $id)
    {
        $this->authorizerModel($this->leftModel)->allowsUpdate($request, $id);
        $rightItem = $this->rightModel::findOrFail($request->input('member_id'));
        $leftItem = $this->leftModel::findOrFail($id);
        $start = $leftItem->{$this->rightStoreName}();

        // Pivot filter
        $pivot = $request->toArray();
        unset($pivot['member_id']);
        foreach ($pivot as $prop => $val) {
            $start = $start->wherePivot($prop, $val);
        }
        // Ids result
        $ids = $start->get()->pluck('id')->toArray();
        if (!in_array($rightItem->id, $ids)) {
            $this->guardRegisterRequest($request, $leftItem, $rightItem);
            $start->attach($rightItem->id, $pivot);
        } else {
            $this->guardUnregisterRequest($request, $leftItem, $rightItem);
            $start->detach($rightItem->id, $pivot);
        }
        return response('No Content', 204);
    }


}
