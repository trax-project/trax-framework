<?php

namespace Trax\DataStore\Http\Controllers;

use App\Http\Controllers\Controller;

use Trax\DataStore\MainDataStoreServices;

class DataStoreController extends Controller
{    
    /**
     * Data Services.
     */
    protected $services;

    /**
     * Data Store.
     */
    protected $store;

    /**
     * Prefered output format (object | id).
     */
    protected $format = 'object';

    /**
     * Filters: used in some circonstances to pass filters to the controller.
     */
    protected $filters = [];

    /**
     * Options: used in some circonstanced to pass options to the controller.
     */
    protected $options = [];

    
    /**
     * Create a new controller instance.
     */
    public function __construct(MainDataStoreServices $services)
    {
        $this->services = $services;
        $this->store = $this->services->datas();
    }
    
}
