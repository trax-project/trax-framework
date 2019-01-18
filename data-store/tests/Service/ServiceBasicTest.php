<?php

namespace Trax\DataStore\Tests\Service;

class ServiceBasicTest extends ServiceTest
{
    
    public function setUp()
    {
        parent::setUp();
        $this->store->clear();
    }
    
    public function test_store()
    {
        // Given a data record
        $data = $this->data();
        
        // When I post it
        $response = $this->auth()->json('POST', $this->endpoint(), $data, $this->authHeaders());

        // I get no error and I get the record ID
        $this->assertStatus($response, 200);
        $id = $this->jsonResponse($response);
        $this->assertRecord($id, $data);
    }

    public function test_clear()
    {
        // Given a store with a data record
        $this->store->store($this->data());

        // When I clear the store
        $response = $this->auth()->json('DELETE', $this->endpoint(), [], $this->authHeaders());
        
        // Then I get no error and I count 0 data record
        $this->assertStatus($response, 204);
        $this->assertTrue($this->store->count() == 0);
    }
    
    public function test_count()
    {
        // Given a store with 2 data records
        $this->store->store($this->data());
        $this->store->store($this->data());
        
        // When I count the data records
        $response = $this->auth()->json('GET', $this->endpoint().'/count', [], $this->authHeaders());

        // Then I get no error and I get the right number
        $this->assertStatus($response, 200);
        $nb = $this->jsonResponse($response);
        $this->assertTrue($nb == 2);
    }
    
    public function test_find()
    {
        // Given a store with a data record
        $data = $this->data();
        $id = $this->store->store($data);
        
        // When I try to get it
        $response = $this->auth()->json('GET', $this->endpoint().'/'.$id, [], $this->authHeaders());
        
        // Then it is found and correct
        $this->assertStatus($response, 200);
        $id = $this->jsonResponse($response);
        $this->assertRecord($id, $data);
    }

    public function test_get()
    {
        // Given a store with a data record
        $data = $this->data();
        $id = $this->store->store($data);
        
        // When I try to get all the data records
        $response = $this->auth()->json('GET', $this->endpoint(), [], $this->authHeaders());
        
        // Then they are returned and correct
        $this->assertStatus($response, 200);
        $res = $this->jsonResponse($response);
        $this->assertTrue(is_object($res));
        $this->assertTrue(isset($res->data));
        $this->assertTrue(isset($res->total_count));
        $this->assertTrue(isset($res->filtered_count));

        // And count information is correct
        $this->assertTrue(is_array($res->data));
        $this->assertTrue(count($res->data) == 1);
        $this->assertTrue($res->total_count == 1);
        $this->assertTrue($res->filtered_count == 1);

        // And record value is correct
        $this->assertRecord($res->data[0], $data);
    }

    public function test_update()
    {
        // Given a store with a data record
        $id = $this->store->store($this->data());
        
        // When I update this data record
        $data = $this->auth()->data();
        $response = $this->json('PUT', $this->endpoint().'/'.$id, $data, $this->authHeaders());

        // Then I get the ID in response and it is correct
        $this->assertStatus($response, 200);
        $id = $this->jsonResponse($response);
        $this->assertRecord($id, $data);
    }
    
    public function test_delete()
    {
        // Given a store with a data record
        $id = $this->store->store($this->data());
        
        // When I delete this data record
        $response = $this->auth()->json('DELETE', $this->endpoint().'/'.$id, [], $this->authHeaders());
        
        // Then I get a correct response status
        $this->assertStatus($response, 204);
        
        // Then I count 0 data record
        $this->assertTrue($this->store->count() == 0);
    }

    public function test_delete_non_existing_id()
    {
        // Given there is no data record with the ID 999999
        
        // When I try to delete the data record with a 999999 id
        $response = $this->auth()->json('DELETE', $this->endpoint().'/999999', [], $this->authHeaders());

        // I get an error
        $this->assertStatus($response, 404);
    }

}
