<?php

namespace Trax\Account\Console;

class ClientCreateCommand extends ClientCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'client:create {name}';

    /**
     * The console command description.
     */
    protected $description = 'Create a new client';

    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('');
        
        // Create the account
        $name = $this->argument('name');
        if ($name == 'test') {

            // Special account for tests
            $account = array(
                'name' => 'Test Suite',
                'username' => 'testsuite',
                'password' => 'password',
            );
        } else {

            // Generated IDs account
            $account = array(
                'name' => $this->argument('name'),
                'username' => traxUuid(),
                'password' => traxUuid(),
            );
        }

        $account = $this->store->store($account, ['format'=>'object']);
        
        // Display it
        $this->line('Created client: '.$account->data->name);
        $this->line('Username: '.$account->username);
        $this->line('Password: '.$account->password);
    }

}