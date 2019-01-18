<?php

namespace Trax\Account\Console;

class UserCreateCommand extends UserCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'user:create {email} {firstname} {lastname}';

    /**
     * The console command description.
     */
    protected $description = 'Create a new user';

    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('');
        
        // Get the account
        try {
            $account = $this->store->findBy('email', $this->argument('email'));
            return $this->error("The specified user already exists.");
        } catch (\Exception $e) {
        }

       // Create the account
        $account = array(
            'email' => $this->argument('email'),
            'firstname' => $this->argument('firstname'),
            'lastname' => $this->argument('lastname'),
            'password' => \Faker\Factory::create()->password,
        );
        $id = $this->store->store($account);
        
        // Display it
        $headers = ['Email', 'Firstname', 'Lastname', 'Password'];
        $this->table($headers, [$account]);
    }

}