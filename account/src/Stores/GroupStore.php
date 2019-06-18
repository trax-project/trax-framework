<?php

namespace Trax\Account\Stores;

use Trax\DataStore\Stores\DataStoreFilter;
use Trax\DataStore\Stores\SelectableStore;

traxCreateDataStoreSwitchClass('Trax\Account\Stores', 'trax-account', 'Group');

class GroupStore extends GroupStoreSwitch
{
    use DataStoreFilter, SelectableStore;

    /**
     * Props used for the global search.
     */
    protected $globalSearchScopes = array('data.name');

    /**
     * Filters.
     */
    protected $filters = [
        'ids' => ['type' => 'In', 'target' => 'id'],
        'status_code' => ['type' => 'Equal', 'target' => 'data.status_code'],
    ];

}
