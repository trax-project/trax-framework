<?php

namespace Trax\Account\Tests\BasicClient\Service;

use Trax\DataStore\Tests\Service\ServiceGetTest;
use Trax\Account\Tests\BasicClient\Store\BasicClientStoreTest;

class BasicClientServiceGetTest extends ServiceGetTest
{
    use BasicClientStoreTest;
    
    /**
     * Deactivate auth because it would interfer with these tests.
     */
    protected $auth = false;      
    

    public function test_findby()
    {
        // Given a store with a data record
        $data = $this->data();
        $data['name'] = strtolower($data['name']);        // Lowercase only !!!!!
        $id = $this->store->store($data);
        
        // When I try to find it
        $url = $this->endpoint().'/findby?'.http_build_query(['data.name' => $data['name']]);
        $response = $this->auth()->json('GET', $url, $this->authHeaders());

        // Then I get the right data record
        $this->assertStatus($response, 200);
        $id = $this->jsonResponse($response);
        $this->assertRecord($id, $data);
    }
    

}
