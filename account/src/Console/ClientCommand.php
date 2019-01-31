<?php

namespace Trax\Account\Console;

use Trax\DataStore\Console\DataCommand;

use TraxAccount;

class ClientCommand extends DataCommand
{

    /**
     * Get store.
     */
    protected function store()
    {
        return TraxAccount::basicClients();
    }
    
}