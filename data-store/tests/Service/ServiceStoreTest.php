<?php

namespace Trax\DataStore\Tests\Service;

class ServiceStoreTest extends ServiceTest
{

    public function setUp()
    {
        parent::setUp();
        $this->store->clear();
    }
    
    public function test_store_invalid_string()
    {
        // Given a data record
        $data = $this->data();
        
        // When I post it
        $headers = $this->transformHeadersToServerVars($this->authHeaders());
        $response = $this->auth()->call('POST', $this->endpoint(), [], [], [], $headers, json_encode($data).'error');
        
        // I get an error
        $this->assertStatus($response, 400);
    }
    
    public function test_update_invalid_string()
    {
        // Given a store with a data record
        $id = $this->store->store($this->data());
        
        // When I update this data record
        $data = $this->data();
        $headers = $this->transformHeadersToServerVars($this->authHeaders());
        $response = $this->auth()->call('PUT', $this->endpoint().'/'.$id, [], [], [], $headers, json_encode($data).'error');
        
        // I get an error
        $this->assertStatus($response, 400);
    }
    
    public function test_update_non_existing_data()
    {
        // Given there is no data record with the ID 999
        
        // When I try to update the data record with a 999 id
        $data = $this->data();
        $response = $this->auth()->json('PUT', $this->endpoint().'/999', $data, $this->authHeaders());

        // I get an error
        $this->assertStatus($response, 404);
    }


    //------------------------------------------- Utilities ---------------------------------------//
     

    protected function assertStoreRequiredProp($field)
    {
        // Given a data record
        $data = $this->data();
        unset($data[$field]);
        
        // When I post it
        $response = $this->auth()->json('POST', $this->endpoint(), $data, $this->authHeaders());

        // I get an error
        $this->assertStatus($response, $this->errorCode());
    }
    
    protected function assertStoreUniqueProp($field, $unique = true)
    {
        // Given 2 data records with a common prop
        $data1 = $this->data();
        $data2 = $this->data();
        $data2[$field] = $data1[$field];
        
        // When I post the first one
        $response = $this->auth()->json('POST', $this->endpoint(), $data1, $this->authHeaders());
        
        // That's OK
        $this->assertStatus($response, 200);
        
        // When I post the 2nd one
        $response = $this->auth()->json('POST', $this->endpoint(), $data2, $this->authHeaders());
        
        // I get an error or not
        if ($unique) $this->assertStatus($response, $this->errorCode());
        else $this->assertStatus($response, 200);
    }

    protected function assertStorePropSize($field, $size, $operator = 'max')
    {
        // Given a data record
        $data1 = $this->data();
        $data2 = $this->data();
        $less = max(0, $size-1);
        $more = $size+1;
        $data1[$field] = $this->string($less);
        $data2[$field] = $this->string($more);

        // When I post a smaller value
        $response = $this->auth()->json('POST', $this->endpoint(), $data1, $this->authHeaders());

        // I get an error or not
        if ($operator == 'max') $this->assertStatus($response, 200);
        else $this->assertStatus($response, $this->errorCode());
    
        // When I post a greater value
        $response = $this->auth()->json('POST', $this->endpoint(), $data2, $this->authHeaders());
        
        // I get an error or not
        if ($operator == 'max') $this->assertStatus($response, $this->errorCode());
        else $this->assertStatus($response, 200);
    }
    
    protected function string($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    protected function errorCode()
    {
        return ($this->auth == 'user' ? 422 : 400);
    }

}
