<?php

namespace Trax\Account\Stores;

traxCreateDataStoreSwitchClass('Trax\Account\Stores', 'trax-account', 'BasicClient');

class BasicClientStore extends BasicClientStoreSwitch
{
    /**
     * Props used for the global search.
     */
    protected $globalSearchScopes = array('username', 'data.name');
        
        
}
