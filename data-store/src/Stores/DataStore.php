<?php

namespace Trax\DataStore\Stores;

traxCreateDataStoreSwitchClass('Trax\DataStore\Stores', 'trax-data-store', 'Data');

class DataStore extends DataStoreSwitch
{
    /**
     * Data used for the global search.
     * This list has been defined here for testing purpose.
     */
    protected $globalSearchScopes = ['data.firstname', 'data.lastname'];
    
    
}
