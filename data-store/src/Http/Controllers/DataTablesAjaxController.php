<?php

namespace Trax\DataStore\Http\Controllers;

use Illuminate\Http\Request;

trait DataTablesAjaxController
{
    protected $datatableOptions = ['count' => true];


    /**
     * Get data entries.
     */
    public function datatablesGet(Request $request)
    {
        // Permissions & validations
        $search = $this->allowsRead($request, null);
        $this->guardGetRequest($request);
        $this->validateGetRequest($request);

        // Collect datatables args        
        $args = array();
        
        // - Limit
        if ($request->has('length')) $args['limit'] = $request->input('length');
        
        // - Offset
        if ($request->has('start')) $args['offset'] = $request->input('start');
        
        // - Order by & dir
        if ($request->has('order')) {
            $columnNum = $request->input('order.0.column');
            $columnName = $request->input('columns.'.$columnNum.'.name');
            if (!empty($columnName)) {
                $args['order-by'] = $columnName;
                $args['order-dir'] = $request->input('order.0.dir');
            }
        }
        if ($request->has('order-by')) $args['order-by'] = $request->input('order-by');
        if ($request->has('order-dir')) $args['order-dir'] = $request->input('order-dir');

        // - Search
        if ($request->has('search.value') && !empty($request->input('search.value'))) {
            $args['global-search'] = $request->input('search.value');
        }
        $this->search($args, $search);
        
        // - Filters
        $filters = [];
        if ($request->has('filters')) $filters = $request->input('filters');
        $filters = array_merge($filters, $this->filters);
        if (!empty($filters)) $args['filters'] = $filters;

        // - With (relations)

        if ($request->has('with')) $this->datatableOptions['with'] = $request->input('with');

        // - Options
        $options = array_merge($this->datatableOptions, $this->options);

        // Get data
        $res = $this->store->get($args, $options);

        // Returns additional datatables information
        $resp = (object)array("data" => $res->data);
        $resp->draw = intval($request->input('draw'));
        $resp->recordsTotal = $res->total_count;
        $resp->recordsFiltered = $res->filtered_count;
        
        return response()->json($resp);
    }
}
