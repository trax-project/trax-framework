<?php

namespace Trax\DataStore\Tests\Service;

class ServiceGetTest extends ServiceTest
{
    
    public function setUp()
    {
        parent::setUp();
        $this->store->clear();
    }
    
    public function test_find_non_existing_id()
    {
        // Given there is no data record with the ID 999
        
        // When I try to find the data record with a 999 id
        $response = $this->auth()->json('GET', $this->endpoint().'/999', [], $this->authHeaders());

        // I get an error
        $this->assertStatus($response, 404);
    }

    public function test_findby()
    {
        // Given a store with a data record
        $data = $this->data();
        $data['firstname'] = strtolower($data['firstname']);        // Lowercase only !!!!!
        $id = $this->store->store($data);
        
        // When I try to find it
        $url = $this->endpoint().'/findby?'.http_build_query(['data.firstname' => $data['firstname']]);
        $response = $this->auth()->json('GET', $url, [], $this->authHeaders());

        // Then I get the right data record
        $this->assertStatus($response, 200);
        $id = $this->jsonResponse($response);
        $this->assertRecord($id, $data);
    }
    
    public function test_findby_unknown_value()
    {
        // Given a store with a data record
        $this->store->store($this->data());
        
        // When I try to find it
        $url = $this->endpoint().'/findby?'.http_build_query(['data.firstname' => 'unknown']);
        $response = $this->auth()->json('GET', $url, [], $this->authHeaders());

        // I get an error
        $this->assertStatus($response, 404);
    }

    public function test_get_paginated()
    {
        // Given a store with 3 data records
        $id1 = $this->store->store($this->data());
        $id2 = $this->store->store($this->data());
        $id3 = $this->store->store($this->data());
        
        // When I try to get the 2nd page with 2 records per page
        $url = $this->endpoint().'?'.http_build_query(['limit'=>2, 'offset'=>2]);
        $response = $this->auth()->json('GET', $url, [], $this->authHeaders());
        
        // Then I get only the last data
        $this->assertStatus($response, 200);
        $res = $this->jsonResponse($response);
        $records = $res->data;
        $this->assertTrue(count($records) == 1);
        $this->assertTrue($records[0]->id == $id3);
    }


}
