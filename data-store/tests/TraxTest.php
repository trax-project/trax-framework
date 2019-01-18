<?php

namespace Trax\DataStore\Tests;

use Tests\TestCase;
use Faker\Factory;

use TraxDataStore;

abstract class TraxTest extends TestCase
{ 
    /**
     * Endpoint API to access web services. May be overridden.
     */
    protected $endpointApi = 'data-store/datas';
    
    /**
     * Auth driver to be used with services (false | basic | user)
     * Options other than false require Trax Account package to be installed.
     * May be overridden.
     */
    protected $auth = false;      
    
  /**
     * Endpoint base to access web services.
     */
    protected $endpointBase;
    
    /**
     * Data store
     */
    protected $store;
    
    /**
     * Faker
     */
    protected $faker;

          
    /**
     * Setup
     */
    public function setUp()
    {
        parent::setUp();
        $this->store = $this->store();
        $this->faker = Factory::create();
        $this->endpointBase = ($this->auth == 'user') ? 'trax/ajax/' : 'trax/ws/';
        if (!$this->auth) $this->withoutMiddleware();
    }
    
    /**
     * Get the store to be tested. May be overridden.
     */
    protected function store()
    {
        return TraxDataStore::datas();
    }
    
    /**
     * Generate data.
     */
    abstract protected function data($key = null);

    /**
     * Get the endpoint.
     */
    protected function endpoint($api = null)
    {
        if (isset($api)) return $this->endpointBase.$api;
        return $this->endpointBase.$this->endpointApi;
    }
    
    /**
     * Validate a record compared to its source (both in object formats). 
     * This function only compares properties values at the first level. May be overridden.
     */
    protected function validateRecord($record, $source)
    {
        $diff = array_diff_assoc((array)$source, (array)$record->data);
        return empty($diff);
    }
    

    //------------------------------------------- Assertions ---------------------------------------//
     
    /**
     * Assert response status.
     */
    public function assertStatus($response, $code)
    {
        if (traxRunningInLumen()) {
            $this->assertResponseStatus($code);
        } else {
            $response->assertStatus($code);
        }
    }

    /**
     * Assert a header prop value.
     */
    public function assertHeader($response, $name, $value = null)
    {
        $header = $response->headers->get($name);
        $this->assertTrue($header !== null);
        if (isset($value)) $this->assertTrue(strpos($header, $value) !== false);
    }
    
    /**
     * Assert a record value compared to its source.
     */
    protected function assertRecord($record, $source, $dataProp = true)
    {
        // Get record from ID
        if (is_integer($record) || is_string($record)) {
            $record = $this->store->find($record);
        }
        
        // Be sure that record has an object format
        $record = json_decode(json_encode($record));
        
        // Be sure that data has an object format
        if ($dataProp) {
            if (is_string($record->data)) $record->data = json_decode($record->data);
            else $record->data = json_decode(json_encode($record->data));
        }
        
        // Be sure to convert source to object (easier to compare with resulting data)
        $source = json_decode(json_encode($source));
        
        // Now assert
        $this->assertTrue($this->validateRecord($record, $source));
    }
    

    //------------------------------------------- Utilities ---------------------------------------//
    
    
    /**
     * Return the response to a request.
     */
    protected function responseContent($response)
    {
        if (traxRunningInLumen()) {
            return $response->response->getContent();          
        } else {
            return $response->getContent();
        }
    }

    /**
     * Return the JSON response to a request.
     */
    protected function jsonResponse($response)
    {
        return json_decode($this->responseContent($response));
    }


}
