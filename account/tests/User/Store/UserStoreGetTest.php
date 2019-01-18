<?php

namespace Trax\Account\Tests\User\Store;

use Trax\DataStore\Tests\Store\StoreGetTest;

class UserStoreGetTest extends StoreGetTest
{
    use UserStoreTest;


    /**
     * Test search on email (property).
     * Test search on firstname and lastname (virtual columns).
     */

    public function test_get_ordered()
    {
        $this->assertOrdering('id', true);
        $this->assertOrdering('email', true);
        $this->assertOrdering('firstname');
        $this->assertOrdering('lastname');    
    }

    public function test_search_simple()
    {
        $this->assertSearch('email', 'email');
        $this->assertSearch('firstname', 'firstname');
        $this->assertSearch('lastname', 'lastname');
    }    

    public function test_search_multi()
    {
        $this->assertSearchMulti('email', 'email', 'firstname', 'firstname');
        $this->assertSearchMulti('email', 'email', 'lastname', 'lastname');
        $this->assertSearchMulti('firstname', 'firstname', 'lastname', 'lastname');
    }    
    
    public function test_search_case()
    {
        $this->assertSearchCase('email', 'email');
        $this->assertSearchCase('firstname', 'firstname');
        $this->assertSearchCase('lastname', 'lastname');
    }    

}
