<?php

namespace Trax\Account\Tests\BasicClient\Service;

use Trax\DataStore\Tests\Service\ServiceBasicTest;
use Trax\Account\Tests\BasicClient\Store\BasicClientStoreTest;

class BasicClientServiceBasicTest extends ServiceBasicTest
{
    use BasicClientStoreTest;
    
    /**
     * Deactivate auth because it would interfer with these tests.
     */
    protected $auth = false;      


}
