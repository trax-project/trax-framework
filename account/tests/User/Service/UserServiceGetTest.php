<?php

namespace Trax\Account\Tests\User\Service;

use Trax\DataStore\Tests\Service\ServiceGetTest;
use Trax\Account\Tests\User\Store\UserStoreTest;

class UserServiceGetTest extends ServiceGetTest
{
    use UserStoreTest;

    /**
     * Deactivate auth because it would interfer with these tests.
     */
    protected $auth = false;      
    
    
}
