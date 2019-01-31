<?php

namespace Trax\DataStore\Tests\Service;

use Trax\DataStore\Tests\Store\StoreTest;

class ServiceTest extends StoreTest
{

    /**
     * Allow user authentication.
     */
    protected function auth($createIfMissing = true)
    {
        // No auth
        if ($this->auth != 'user') return $this;
        
        // Define a common user for tests
        $user = [
            'email' => 'test@traxlrs.com',
            'firstname' => $this->faker->firstname,
            'lastname' => $this->faker->lastname,
        ];
        
        // If no client registered, create one
        if ($createIfMissing) {
            $userStore = \TraxAccount::users();
            try {
                $storeUser = $userStore->findBy('email', $user['email']);
            } catch (\Exception $e) {
                $storeUser = $userStore->store($user, ['format' => 'object']);
            }
        }

        // Get authenticatable user
        $authUser = \Trax\Account\Models\User::find($storeUser->id);
        return $this->actingAs($authUser);
    }

    /**
     * Provide the auth headers for service requests.
     */
    protected function authHeaders($createIfMissing = true)
    {
        // No auth
        if (!in_array($this->auth, ['basic'])) return array();
        
        // Basic HTTP
        if ($this->auth == 'basic') return $this->authHeadersBasic($createIfMissing);
    }

    /**
     * Provide basic auth headers for service requests.
     */
    protected function authHeadersBasic($createIfMissing = true)
    {
        // Define a common client for all tests
        $client = [
            'name' => 'Test Suite',
            'username' => 'testsuite',
            'password' => 'password',
        ];
        
        // If no client registered, create one
        if ($createIfMissing) {
            $clientStore = \TraxAccount::basicClients();
            try {
                $clientStore->findBy('username', $client['username']);
            } catch (\Exception $e) {
                $id = $clientStore->store($client);
            }
        }
        
        // Return headers
        return array(
            "Authorization" => "Basic " . base64_encode($client['username'] . ":" . $client['password']),
            //"HTTP_Authorization" => "Basic " . base64_encode($username . ":" . $password),
            //"PHP_AUTH_USER" => $username, // must add this header since PHP won't set it correctly
            //"PHP_AUTH_PW" => $password // must add this header since PHP won't set it correctly as well
        );
    }

}
