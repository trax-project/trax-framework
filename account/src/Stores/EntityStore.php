<?php

namespace Trax\Account\Stores;

traxCreateDataStoreSwitchClass('Trax\Account\Stores', 'trax-account', 'Entity');

use Trax\DataStore\Stores\StructStore;

class EntityStore extends EntityStoreSwitch
{
    use StructStore;

    /**
     * Sortable store.
     */
    protected $sortable = false;

    /**
     * Split items by type code.
     */
    protected $slitByType = true;

}
