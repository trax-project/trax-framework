<?php

namespace Trax\Account\Tests\BasicClient\Store;

use Trax\DataStore\Tests\Store\StoreBasicTest;

class BasicClientStoreBasicTest extends StoreBasicTest
{
    use BasicClientStoreTest;
    

    public function test_create_xapi_client()
    {
        // Given a data record
        $data = [
            'name' => 'Test',
            'username' => 'testsuite',
            'password' => 'password',
        ];

        // When I store the data record
        $id = $this->store->store($data);
        
        // Then I get an ID
        $this->assertTrue($id !== false);
    }
    

}
