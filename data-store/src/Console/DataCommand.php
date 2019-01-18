<?php

namespace Trax\DataStore\Console;

use Illuminate\Console\Command;

use TraxDataStore;

class DataCommand extends Command
{
    /**
     * Store.
     */
    protected $store;
    
        
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->store = $this->store();
    }

    /**
     * Get store.
     */
    protected function store()
    {
        return TraxDataStore::datas();
    }

    /**
     * Get formated object.
     */
    protected function formatObject($data)
    {
        $res = [];
        $items = explode(';', $data);
        foreach($items as $item) {
            $parts = explode(':', $item);
            if (count($parts) != 2) continue;
            $prop = trim($parts[0]);
            if (empty($prop)) continue;
            $res[$prop] = trim($parts[1]);
        }
        return $res;
    }

}