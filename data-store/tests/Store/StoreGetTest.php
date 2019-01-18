<?php

namespace Trax\DataStore\Tests\Store;

use Trax\DataStore\Exceptions\NotFoundException;

class StoreGetTest extends StoreTest
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
        try {
            $record = $this->store->find(999);

            // A NotFoundException exception is thrown
            $this->assertTrue(false);

        } catch (\Exception $e) {

            // A NotFoundException exception is thrown
            $this->assertTrue($e instanceof NotFoundException);
        }
    }
    
    public function test_find_and_return_object()
    {
        // Given a store with a data record
        $data = $this->data();
        $id = $this->store->store($data);
        
        // When I try to find it
        $record = $this->store->find($id);
        
        // Then I get data with the right type
        $this->assertTrue(is_object($record));
        $this->assertTrue(is_object($record->data));
        
        // And the data value is correct
        $this->assertRecord($record, $data);
    }
    
    public function test_findby()
    {
        $this->assertFindBy('firstname', 'data.firstname');
        $this->assertFindBy('lastname', 'data.lastname');    
    }

    public function test_findby_unknown_value()
    {
        $this->assertFindByUnknownValue('data.firstname');
        $this->assertFindByUnknownValue('data.lastname');    
    }

    public function test_get_paginated()
    {
        // Given a store with 3 data records
        $id1 = $this->store->store($this->data());
        $id2 = $this->store->store($this->data());
        $id3 = $this->store->store($this->data());
        
        // When I try to get the 2nd page with 2 records per page
        $records = $this->store->get(array('limit'=>2, 'offset'=>2));
        
        // Then I get only the last data
        $this->assertTrue(count($records) == 1);
        $this->assertTrue($records[0]->id == $id3);
    }

    public function test_get_with_count()
    {
        // Given a store with 3 data records
        $id1 = $this->store->store($this->data());
        $id2 = $this->store->store($this->data());
        $id3 = $this->store->store($this->data());
        
        // When I try to get the 2nd page with 2 records per page
        $res = $this->store->get(array(), array('count'=>true));
        
        // Then I get an object with count information
        $this->assertTrue(is_object($res));
        $this->assertTrue(isset($res->data));
        $this->assertTrue(isset($res->total_count));
        $this->assertTrue(isset($res->filtered_count));
        
        // And count information is correct
        $this->assertTrue(count($res->data) == 3);
        $this->assertTrue($res->total_count == 3);
        $this->assertTrue($res->filtered_count == 3);
    }

    public function test_get_since()
    {
        // Given a store with 1 data record
        $record = $this->store->store($this->data(), ['format' => 'object']);
        $date = $record->created_at;
        if (!is_string($date)) $date = $date->toDateTimeString();
        $date = date('c', strtotime($date));
        $previousDate = date('c', strtotime($date)-1);

        // When I try to get all the records since its date
        $records = $this->store->get(array('since' => $date));

        // Then I get 0 record (it is exclusive)
        $this->assertTrue(count($records) == 0);

        // When I try to get all the records since a past date
        $records = $this->store->get(array('since' => $previousDate));
        
        // Then I get 1 record
        $this->assertTrue(count($records) == 1);
    }

    public function test_get_until()
    {
        // Given a store with 1 data record
        $record = $this->store->store($this->data(), ['format' => 'object']);
        $date = $record->created_at;
        if (!is_string($date)) $date = $date->toDateTimeString();
        $date = date('c', strtotime($date));
        $previousDate = date('c', strtotime($date)-1);

        // When I try to get all the records until its date
        $records = $this->store->get(array('until' => $date));
        
        // Then I get 1 record (it is inclusive)
        $this->assertTrue(count($records) == 1);

        // When I try to get all the records until a past date
        $records = $this->store->get(array('until' => $previousDate));
        
        // Then I get 0 record
        $this->assertTrue(count($records) == 0);
    }

    public function test_get_ordered()
    {
        $this->assertOrdering('id', true);
        $this->assertOrdering('firstname');
        $this->assertOrdering('lastname');    
    }

    public function test_global_search_simple()
    {
        $this->assertGlobalSearch('firstname');
        $this->assertGlobalSearch('lastname');
    }    

    public function test_global_search_multi()
    {
        $this->assertGlobalSearchMulti('firstname', 'lastname');
    }    

    public function test_global_search_case()
    {
        $this->assertGlobalSearchCase('firstname');
        $this->assertGlobalSearchCase('lastname');
    }    

    public function test_search_simple()
    {
        $this->assertSearch('firstname', 'data.firstname');
        $this->assertSearch('lastname', 'data.lastname');
    }    

    public function test_search_multi()
    {
        $this->assertSearchMulti('firstname', 'data.firstname', 'lastname', 'data.lastname');
    }    
    
    public function test_search_case()
    {
        $this->assertSearchCase('firstname', 'data.firstname');
        $this->assertSearchCase('lastname', 'data.lastname');
    }    


    //------------------------------------------- Utilities ---------------------------------------//
     

    protected function assertFindBy($field, $column)
    {
            // Given a store with a data record
        $data = $this->data();
        $data[$field] = strtolower($data[$field]);        // Lowercase only !!!!!
        $id = $this->store->store($data);
        
        // When I try to find it
        $record = $this->store->findBy($column, $data[$field]);
        
        // Then I get the right data record
        $this->assertTrue(is_object($record));
        $this->assertTrue($id == $record->id);
    }
    
    protected function assertFindByUnknownValue($column)
    {
        // Given a store with a data record
        $this->store->store($this->data());
        
        // When I try to find it
        try {
            $record = $this->store->findBy($column, 'unknown');

            // A NotFoundException exception is thrown
            $this->assertTrue(false);

        } catch (\Exception $e) {

            // A NotFoundException exception is thrown
            $this->assertTrue($e instanceof NotFoundException);
        }
    }
    
    protected function assertOrdering($field, $property = false)
    {
        // Given a store with 2 data records
        $this->store->clear();
        $id1 = $this->store->store($this->data());
        $id2 = $this->store->store($this->data());
        
        // When I try to get the ordered data records 
        $by = $property ? $field : 'data.'.$field;
        $records1 = $this->store->get(array('order-by'=>$by, 'order-dir'=>'asc'));
        $records2 = $this->store->get(array('order-by'=>$by, 'order-dir'=>'desc'));
        
        // Then I get data in the right order
        $this->assertTrue($this->recordValue($records1[0], $field, $property) <= $this->recordValue($records1[1], $field, $property));
        $this->assertTrue($this->recordValue($records2[0], $field, $property) >= $this->recordValue($records2[1], $field, $property));
    }

    protected function assertGlobalSearch($field)
    {
        // Given a store with 2 data records
        $this->store->clear();
        $data = $this->data();
        $data[$field] = strtolower($data[$field]);      // Lowercase only !!!!!!!!!!!!!!!
        $id1 = $this->store->store($data);
        $id2 = $this->store->store($this->data());
        
        // When I search the first data record
        $term = $data[$field];
        $records = $this->store->get(array('global-search'=>$term));

        // Then I get only the first data record
        $this->assertTrue(count($records) == 1);
        $this->assertTrue($records[0]->id == $id1);
    }    

    protected function assertGlobalSearchMulti($field1, $field2)
    {
        // Given a store with 3 data records
        $this->store->clear();
        $data1 = $this->data();
        $data1[$field1] = strtolower($data1[$field1]);      // Lowercase only !!!!!!!!!!!!!!!
        $data2 = $this->data();
        $data2[$field2] = strtolower($data2[$field2]);      // Lowercase only !!!!!!!!!!!!!!!
        $id1 = $this->store->store($data1);
        $id2 = $this->store->store($data2);
        $id3 = $this->store->store($this->data());
        
        // When I search with 2 terms related to 2 data records
        $terms = $data1[$field1].' '.$data2[$field2];
        $records = $this->store->get(array('global-search'=>$terms));

        // Then I get 2 data records
        $this->assertTrue(count($records) == 2);
        $this->assertTrue($records[0]->id == $id1 || $records[1]->id == $id1);
        $this->assertTrue($records[0]->id == $id2 || $records[1]->id == $id2);
    }    

    protected function assertGlobalSearchCase($field)
    {
        // Given a store with 2 data records
        $this->store->clear();
        $data = $this->data();
        $data[$field] = strtolower($data[$field]);      // Lowercase only !!!!!!!!!!!!!!!
        $id1 = $this->store->store($data);
        $id2 = $this->store->store($this->data());
        
        // When I search the first data record
        // $term = strtoupper($data[$field]);
        $term = $data[$field];
        $records = $this->store->get(array('global-search'=>$term));

        // Then I get only the first data record
        $this->assertTrue(count($records) == 1);
        $this->assertTrue($records[0]->id == $id1);
    }

    protected function assertSearch($field, $column)
    {
        // Given a store with 2 data records
        $this->store->clear();
        $data = $this->data();
        $id1 = $this->store->store($data);
        $id2 = $this->store->store($this->data());
        
        // When I search the first data record
        $term = $data[$field];
        $records = $this->store->get(array('search'=>[$column=>$term]));

        // Then I get only the first data record
        $this->assertTrue(count($records) == 1);
        $this->assertTrue($records[0]->id == $id1);
    }    

    protected function assertSearchMulti($field1, $column1, $field2, $column2)
    {
        // Given a store with 3 data records
        $this->store->clear();
        $data1 = $this->data();
        $data2 = $this->data();
        $id1 = $this->store->store($data1);
        $id2 = $this->store->store($data2);
        $id3 = $this->store->store($this->data());
        
        // When I search with 2 terms on the first data record
        $term1 = $data1[$field1];
        $term2 = $data1[$field2];
        $records = $this->store->get(array('search'=>[$column1=>$term1, $column2=>$term2]));

        // Then I get the first data record
        $this->assertTrue(count($records) == 1);
        $this->assertTrue($records[0]->id == $id1);
        
        // When I search with 2 terms on 2 data records
        $term1 = $data1[$field1];
        $term2 = $data2[$field2];
        $records = $this->store->get(array('search'=>[$column1=>$term1, $column2=>$term2]));

        // Then I get no data record
        $this->assertTrue(count($records) == 0);
    }    

    protected function assertSearchCase($field, $column)
    {
        // Given a store with 2 data records
        $this->store->clear();
        $data = $this->data();
        $id1 = $this->store->store($data);
        $id2 = $this->store->store($this->data());
        
        // When I search the first data record
        // $term = strtolower($data[$field]);           // Term is case sensitive !!!
        $term = $data[$field];
        $records = $this->store->get(array('search'=>[$column=>$term]));

        // Then I get only the first data record
        $this->assertTrue(count($records) == 1);
        $this->assertTrue($records[0]->id == $id1);
    }    

    protected function recordValue($record, $field, $property = false)
    {
        // Property
        if ($property) return $record->$field;
        
        // Be sure that record data has an object format
        $data = is_string($record->data) ? json_decode($record->data) : $record->data;

        // JSON element
        $path = explode('.', $field);
        foreach($path as $node) {
            $data = $data->$node;
        }
        return $data;
    }


}
