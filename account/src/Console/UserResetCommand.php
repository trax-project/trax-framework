<?php

namespace Trax\Account\Console;

class UserResetCommand extends UserCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'user:reset {email}';

    /**
     * The console command description.
     */
    protected $description = 'Reset a user password';

    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('');
        
        // Get the account
        try {
            $account = $this->store->findBy('email', $this->argument('email'));
        } catch (\Exception $e) {
            return $this->error("The specified user does not exist.");
        }

        // Create the account
        $newAccount = array(
            'email' => $account->email,
            'firstname' => $account->data->firstname,
            'lastname' => $account->data->lastname,
            'password' => \Faker\Factory::create()->password,
        );
        $id = $this->store->update($account->id, $newAccount);
        
        // Display it
        $this->line('Updated user: ');
        $this->line('Email: '.$newAccount['email']);
        $this->line('Password: '.$newAccount['password']);
    }

}