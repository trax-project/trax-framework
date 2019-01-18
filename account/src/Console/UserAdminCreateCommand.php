<?php

namespace Trax\Account\Console;

class UserAdminCreateCommand extends UserCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'user:create-admin';

    /**
     * The console command description.
     */
    protected $description = 'Create a super-admin user account';

    
    /**
     * Execute the console command.
     */
    public function handle()
    {        
        // Delete existing account
        try {
            $account = $this->store->findBy('email', 'admin@traxlrs.com');
            $this->store->delete($account->id);
        } catch (\Exception $e) {
        }

       // Create the account
        $account = [
            'username' => config('trax-account.auth.username') ? 'admin' : 'admin@traxlrs.com',
            'email' => 'admin@traxlrs.com',
            'firstname' => 'Super',
            'lastname' => 'Admin',
            'password' => \Faker\Factory::create()->password,
            'admin' => true,
        ];
        $this->store->store($account);
        
        // Display it
        $this->line('');
        $headers = ['Username', 'Password'];
        $this->table($headers, [[
            $account['username'],
            $account['password'],
        ]]);
    }

}