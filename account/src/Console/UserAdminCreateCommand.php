<?php

namespace Trax\Account\Console;

class UserAdminCreateCommand extends UserCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'user:create-admin {email=admin@trax.test}';

    /**
     * The console command description.
     */
    protected $description = 'Create a super-admin user account, given an email address (optional)';

    
    /**
     * Execute the console command.
     */
    public function handle()
    {   
        $email = $this->argument('email');
        $username = explode('@', $email)[0];

        // Delete existing account
        try {
            $account = $this->store->findBy('email', $email);
            $this->store->delete($account->id);
        } catch (\Exception $e) {
        }

       // Create the account
        $account = [
            'username' => config('trax-account.auth.username') ? $username : $email,
            'email' => $email,
            'firstname' => 'Super',
            'lastname' => 'Admin',
            'password' => \Faker\Factory::create()->password,
            'admin' => 1,
            'active' => 1,
            'source_code' => 'internal',
            'lang' => 'en',
        ];
        try {
            $this->store->store($account);
        } catch(\Exception $e) {
            $this->line('Account creation failed!');
            return;
        }
        
        // Display it
        $this->line('');
        $headers = ['Identifier', 'Password'];
        $this->table($headers, [[
            $account['username'],
            $account['password'],
        ]]);
    }

}