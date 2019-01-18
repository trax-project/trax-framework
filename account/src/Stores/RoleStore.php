<?php

namespace Trax\Account\Stores;

traxCreateDataStoreSwitchClass('Trax\Account\Stores', 'trax-account', 'Role');

use Trax\DataStore\Stores\SelectableStore;

class RoleStore extends RoleStoreSwitch
{
    use SelectableStore;
    
    /**
     * Props used for the global search.
     */
    protected $globalSearchScopes = array('data.name');

    /**
     * Default ordering settings.
     */
    protected $defaultOrderingCol = 'name';

}
