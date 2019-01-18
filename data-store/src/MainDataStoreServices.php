<?php

namespace Trax\DataStore; 

class MainDataStoreServices extends DataStoreServices
{
    /**
     * Get User store.
     */
    public function datas() {
        return $this->store('Data');
    }

}
