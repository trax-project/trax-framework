<?php

namespace Trax\Account\Tests\BasicClient\Service;

use Trax\DataStore\Tests\Service\ServiceStoreTest;
use Trax\Account\Tests\BasicClient\Store\BasicClientStoreTest;

class BasicClientServiceStoreTest extends ServiceStoreTest
{
    use BasicClientStoreTest;

    /**
     * Deactivate auth because it would interfer with these tests.
     */
    protected $auth = false;      


    public function test_store_missing_props()
    {
        $this->assertStoreRequiredProp('name');
        $this->assertStoreRequiredProp('username');
        $this->assertStoreRequiredProp('password');
    }

    public function test_store_unique_props()
    {
        $this->assertStoreUniqueProp('name', false);
        $this->assertStoreUniqueProp('username');
        $this->assertStoreUniqueProp('password', false);
    }

    public function test_store_props_size()
    {
        $this->assertStorePropSize('name', 255);
        $this->assertStorePropSize('username', 255);
        $this->assertStorePropSize('password', 6, 'min');
    }


}
