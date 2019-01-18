<?php

namespace Trax\Account\Tests\User\Service;

use Trax\DataStore\Tests\Service\ServiceBasicTest;
use Trax\Account\Tests\User\Store\UserStoreTest;

class UserServiceBasicTest extends ServiceBasicTest
{
    use UserStoreTest;
    
    /**
     * Deactivate auth because it would interfer with these tests.
     */
    protected $auth = false;      
    
    
}
