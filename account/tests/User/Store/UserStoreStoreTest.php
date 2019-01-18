<?php

namespace Trax\Account\Tests\User\Store;

use Trax\DataStore\Tests\Store\StoreStoreTest;
use Trax\Account\Models\User;

class UserStoreStoreTest extends StoreStoreTest
{
    use UserStoreTest;

    
    /**
     * Test with password.
     */
    public function test_store_password()
    {
        // Given a data record
        $data = $this->data();
        
        // When I store the data record
        $id = $this->store->store($data);
        $user = User::find($id);

        // Then the password can be checked
        $check = app('hash')->check($data['password'], $user->password);
        $this->assertTrue($check);
    }

    /**
     * Test with empty password.
     */
    public function test_store_empty_password()
    {
        // Given a data record
        $data = $this->data();
        $data['password'] = '';
        
        // When I store the data record
        $id = $this->store->store($data);
        $user = User::find($id);

        // Then the password can be checked
        $check = app('hash')->check($data['password'], $user->password);
        $this->assertTrue(!$check);
    }

    /**
     * Test without password.
     */
    public function test_store_without_password()
    {
        // Given a data record
        $data = $this->data();
        unset($data['password']);
        
        // When I store the data record
        $id = $this->store->store($data);
        $user = User::find($id);

        // Then the password can be checked
        $this->assertTrue(isset($user->password));
        $check = app('hash')->check('', $user->password);
        $this->assertTrue(!$check);
    }

}
