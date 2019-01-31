<?php

namespace Trax\Account\Tests\User\Store;

use TraxAccount;

trait UserStoreTest
{

    /**
     * Get the store to be tested.
     */
    protected function store()
    {
        return TraxAccount::users();
    }
    
    /**
     * Endpoint.
     */
    protected function endpoint($api = null)
    {
        return parent::endpoint('users');
    }
    
    /**
     * Generate data.
     */
    protected function data($key = null)
    {
        $data = array(
            'email' => $this->faker->email,
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'password' => $this->faker->password,
        );
        $data['password_confirmation'] = $data['password'];
        return $data;
    }

    /**
     * Validate a record compared to its source (both in object formats). 
     */
    protected function validateRecord($record, $source)
    {
        $correctValues = (
            $source->email === $record->email
            && $source->firstname === $record->data->firstname
            && $source->lastname === $record->data->lastname
        );
        if (get_class($record) == 'Trax\Account\Models\User') {
                
                // No check on Eloquent models
                $hiddenProps = true;
        } else {
                
                // Check only on database builder
                $hiddenProps = (
                    !isset($record->password)
                    && !isset($record->remember_token)
                    && !isset($record->firstname)
                    && !isset($record->lastname)
                    && !isset($record->data->email)
                    && !isset($record->data->password)
                );
        }
        return $correctValues && $hiddenProps;
    }

}
