<?php

namespace Trax\Account\Console;

class ClientCreateCommand extends ClientCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'client:create {username=testsuite} {password=password}';

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
        
        $username = $this->argument('username');
        $password = $this->argument('password');

        if ($username != 'testsuite' && $password == 'password') $password = traxUuid();
        
        // Create the account
        $account = [
            'username' => $username,
            'password' => $password,
        ];
        try {
            $this->store->store($account);
        } catch (\Exception $e) {
            $this->line('Account creation failed!');
            return;
        }
        
        // Display it
        $this->line('');
        $headers = ['Username', 'Password'];
        $this->table($headers, [[
            $username,
            $password,
        ]]);
    }

}