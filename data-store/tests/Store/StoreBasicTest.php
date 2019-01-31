<?php

namespace Trax\DataStore\Tests\Store;

use Trax\DataStore\Exceptions\NotFoundException;

class StoreBasicTest extends StoreTest
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

        // When I store the data record
        $id = $this->store->store($data);
        
        // Then I get an ID
        $this->assertTrue($id !== false);
    }
    
    public function test_clear()
    {
        // Given a store with a data record
        $this->store->store($this->data());

        // When I clear the store
        $this->store->clear();

        // Then I count 0 data record
        $this->assertTrue($this->store->count() == 0);
    }
    
    public function test_count()
    {
        // Given a store with 2 data records
        $this->store->store($this->data());
        $this->store->store($this->data());
        
        // When I count the data records
        $count = $this->store->count();
        
        // Then I count 2 records
        $this->assertTrue($count == 2);
    }
    
    public function test_find()
    {
        // Given a store with a data record
        $data = $this->data();
        $id = $this->store->store($data);
        
        // When I try to get it
        $record = $this->store->find($id);
        
        // Then it is found
        $this->assertTrue($record !== false);

        // The id is correct
        $this->assertTrue($record->id == $id);

        // The data prop is not encoded
        $this->assertTrue(is_object($record->data));
   
        // And the record value is correct
        $this->assertRecord($record, $data);
    }

    public function test_get()
    {
        // Given a store with a data record
        $data = $this->data();
        $id = $this->store->store($data);
        
        // When I try to get all the data records
        $records = $this->store->get();
        
        // Then I get an array with the recorded data
        $this->assertTrue(count($records) == 1);

        // Its id is correct
        $this->assertTrue($records[0]->id == $id);
        
        // And the data prop is not encoded
        $this->assertTrue(is_object($records[0]->data));
    }
    
    public function test_update()
    {
        // Given a store with a data record
        $id = $this->store->store($this->data());
        
        // When I update this data record
        $data = $this->data();
        $id = $this->store->update($id, $data);

        // Then I get the ID in response
        $this->assertTrue($id !== false);
        
        // And the value of the matching record is correct
        $this->assertRecord($id, $data);
    }
    
    public function test_delete()
    {
        // Given a store with a data record
        $id = $this->store->store($this->data());
        
        // When I delete this data record
        $res = $this->store->delete($id);
        
        // Then I count 0 data record
        $this->assertTrue($this->store->count() == 0);
    }

    public function test_delete_non_existing_id()
    {
        // Given there is no data with the ID 999
        
        // When I try to delete the data with a 999 id
        try {
            $record = $this->store->delete(999);

            // A NotFoundException exception is thrown
            $this->assertTrue(false);
            
        } catch (\Exception $e) {
            
            // A NotFoundException exception is thrown
            $this->assertTrue($e instanceof NotFoundException);
        }
        
    }

}
