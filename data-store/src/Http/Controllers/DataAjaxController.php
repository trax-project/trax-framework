<?php

namespace Trax\DataStore\Http\Controllers;

use Illuminate\Http\Request;

class DataAjaxController extends DataWsController
{
    use DataTablesAjaxController;
    
    /**
     * Get data entries.
     */
    public function get(Request $request)
    {
        // Check if the request comes from DataTable and format the response accordingly.
        if ($request->has('draw')) return $this->datatablesGet($request);
        
        return parent::get($request);
    }
    
    /**
     * Get data entries with a POST method
     */
    public function store(Request $request)
    {
        // Check if the request comes from DataTable and format the response accordingly.
        if ($request->has('draw')) return $this->datatablesGet($request);
        
        return parent::store($request);
    }
    

}
