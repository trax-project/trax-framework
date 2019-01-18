<?php

namespace Trax\Account\Tests\BasicClient\Store;

use TraxAccount;

trait BasicClientStoreTest
{

    /**
     * Get the store to be tested.
     */
    protected function store()
    {
        return TraxAccount::basicClients();
    }
    
    /**
     * Endpoint.
     */
    protected function endpoint($api = null)
    {
        return parent::endpoint('basic-clients');
    }
    
    /**
     * Generate data.
     */
    protected function data($key = null)
    {
        return array(
            'name' => $this->faker->name,
            'username' => $this->faker->uuid,
            'password' => $this->faker->uuid,
        );
    }

    /**
     * Validate a record compared to its source (both in object formats). 
     */
    protected function validateRecord($record, $source)
    {
        $correctValues = (
            $source->name === $record->data->name
            && $source->username === $record->username
            && $source->password === $record->password
        );
        if (get_class($record) == 'Trax\Account\Models\User') {
                
                // No check on Eloquent models
                $hiddenProps = true;
        } else {
                
                // Check only on database builder
                $hiddenProps = (
                    !isset($record->data->username)
                    && !isset($record->data->password)
                );
        }
        return $correctValues && $hiddenProps;
    }

}
