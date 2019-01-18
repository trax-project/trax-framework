<?php

namespace Trax\Account\Tests\User\Service;

use Trax\DataStore\Tests\Service\ServiceStoreTest;
use Trax\Account\Tests\User\Store\UserStoreTest;

class UserServiceStoreTest extends ServiceStoreTest
{
    use UserStoreTest;
    
    /**
     * Deactivate auth because it would interfer with these tests.
     */
    protected $auth = false;      


    public function test_store_missing_props()
    {
        $this->assertStoreRequiredProp('email');
        $this->assertStoreRequiredProp('firstname');
        $this->assertStoreRequiredProp('lastname');
    }

    public function test_store_unique_props()
    {
        $this->assertStoreUniqueProp('email');
        $this->assertStoreUniqueProp('firstname', false);
        $this->assertStoreUniqueProp('lastname', false);
    }

    public function test_store_props_size()
    {
        $this->assertStorePropSize('firstname', 255);
        $this->assertStorePropSize('lastname', 255);
    }
    
}
