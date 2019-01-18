<?php

namespace Trax\Account\Tests\BasicClient\Store;

use Trax\DataStore\Tests\Store\StoreGetTest;

class BasicClientStoreGetTest extends StoreGetTest
{
    use BasicClientStoreTest;

    
    public function test_findby()
    {
        $this->assertFindBy('name', 'data.name');
        $this->assertFindBy('username', 'username');    
    }

    public function test_findby_unknown_value()
    {
        $this->assertFindByUnknownValue('data.name');
        $this->assertFindByUnknownValue('username');    
    }

    public function test_get_ordered()
    {
        $this->assertOrdering('id', true);
        $this->assertOrdering('name');
        $this->assertOrdering('username', true);    
    }

    public function test_global_search_simple()
    {
        $this->assertGlobalSearch('name');
        $this->assertGlobalSearch('username');
    }    

    public function test_global_search_multi()
    {
        $this->assertGlobalSearchMulti('name', 'username');
    }    

    public function test_global_search_case()
    {
        $this->assertGlobalSearchCase('name');
        $this->assertGlobalSearchCase('username');
    }    

    public function test_search_simple()
    {
        $this->assertSearch('name', 'data.name');
        $this->assertSearch('username', 'username');
    }    

    public function test_search_multi()
    {
        $this->assertSearchMulti('name', 'data.name', 'username', 'username');
    }    
    
    public function test_search_case()
    {
        $this->assertSearchCase('name', 'data.name');
        $this->assertSearchCase('username', 'username');
    }    

    
}
