<?php

namespace Trax\DataStore\Http\Responses;

trait DataTablesResponse
{

    /**
     * Return Datatables Response
     */
    protected function datatablesResponse($data) 
    {
        return response()->json([
            'data' => $data,
            'draw' => 1,
            'recordsFiltered' => count($data),
            'recordsTotal' => count($data),
        ]);
    }
    
    
}

