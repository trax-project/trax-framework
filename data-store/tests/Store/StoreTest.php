<?php

namespace Trax\DataStore\Tests\Store;

use Trax\DataStore\Tests\TraxTest;

class StoreTest extends TraxTest
{

    /**
     * Generate data.
     */
    protected function data($key = null)
    {
        return array(
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
        );
    }

}
