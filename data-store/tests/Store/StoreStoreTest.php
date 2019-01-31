<?php

namespace Trax\DataStore\Tests\Store;

use Trax\DataStore\Exceptions\DataInputException;
use Trax\DataStore\Exceptions\NotFoundException;

class StoreStoreTest extends StoreTest
{
    
    public function setUp()
    {
        parent::setUp();
        $this->store->clear();
    }
    
    public function test_store_array()
    {
        // Given a data record
        $data = $this->data();

        // When I store the data record
        $id = $this->store->store($data);
        
        // Then I get an ID
        $this->assertTrue($id !== false);
        
        // And the recorded data is correct
        $this->assertRecord($id, $data);
    }
    
    public function test_store_object()
    {
        // Given a data record
        $data = $this->data();
        
        // When I store the data record
        $id = $this->store->store((object)$data);
        
        // Then I get an ID
        $this->assertTrue($id !== false);

        // And the recorded data is correct
        $this->assertRecord($id, $data);
    }

    public function test_store_valid_string()
    {
        // Given a data record
        $data = $this->data();
        
        // When I store the data record
        $id = $this->store->store(json_encode($data));
        
        // Then I get an ID
        $this->assertTrue($id !== false);
        
        // And the recorded data is correct
        $this->assertRecord($id, $data);
    }
    
    public function test_store_invalid_string()
    {
        // Given a data record
        $data = $this->data();
        
        // When I store the data record
        try {
            $id = $this->store->store(json_encode($data).'error');

            // A DataInputException exception is thrown
            $this->assertTrue(false);

        } catch (\Exception $e) {

            // A DataInputException exception is thrown
            return $this->assertTrue($e instanceof DataInputException);
        }
    }
    
    public function test_store_and_return_object()
    {
        // Given a data record
        $data = $this->data();
        
        // When I store the data record
        $record = $this->store->store($data, array('format'=>'object'));
        
        // Then I get data with the right type
        $this->assertTrue(is_object($record));
        $this->assertTrue(is_object($record->data));
        
        // And the data value is correct
        $this->assertRecord($record, $data);
    }

    public function test_update_with_array()
    {
        // Given a store with a data record
        $id = $this->store->store($this->data());
        
        // When I update this data record
        $data = $this->data();
        $id = $this->store->update($id, $data);
        
        // Then the recorded data is correct
        $this->assertRecord($id, $data);
    }

    public function test_update_with_object()
    {
        // Given a store with a data record
        $id = $this->store->store($this->data());
        
        // When I update this data record
        $data = $this->data();
        $id = $this->store->update($id, (object)$data);
        
        // Then the recorded data is correct
        $this->assertRecord($id, $data);
    }

    public function test_update_with_valid_string()
    {
        // Given a store with a data record
        $id = $this->store->store($this->data());
        
        // When I update this data record
        $data = $this->data();
        $id = $this->store->update($id, json_encode($data));
        
        // Then the recorded data is correct
        $this->assertRecord($id, $data);
    }

    public function test_update_with_invalid_string()
    {
        // Given a store with a data record
        $id = $this->store->store($this->data());
        
        // When I update this data record
        try {
            $id = $this->store->update($id, json_encode($this->data()).'error');

            // A DataInputException exception is thrown
            $this->assertTrue(false);

        } catch (\Exception $e) {

            // A DataInputException exception is thrown
            return $this->assertTrue($e instanceof DataInputException);
        }        
    }

    public function test_update_non_existing_data()
    {
        // Given there is no data record with the ID 999
        
        // When I try to update the data record with a 999 id
        try {
            $record = $this->store->update(999, $this->data(1));

            // A NotFoundException exception is thrown
            $this->assertTrue(false);

        } catch (\Exception $e) {

            // A NotFoundException exception is thrown
            return $this->assertTrue($e instanceof NotFoundException);
        }
    }

    public function test_update_and_return_object()
    {
        // Given a store with a data record
        $id = $this->store->store($this->data());
        
        // When I store this data
        $data = $this->data();
        $record = $this->store->update($id, $data, array('format'=>'object'));
        
        // Then I get data with the right type
        $this->assertTrue(is_object($record));
        $this->assertTrue(is_object($record->data));
        
        // And the data value is correct
        $this->assertRecord($record, $data);
    }
    

}
