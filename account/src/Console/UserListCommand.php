<?php

namespace Trax\Account\Console;

class UserListCommand extends UserCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'user:list';

    /**
     * The console command description.
     */
    protected $description = 'List users';

    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('');
        
        // Get the accounts
        $accounts = $this->store->get()->map(function ($account) {
            return array(
                $account->email,
                $account->data->firstname,
                $account->data->lastname,
            );
        });

        // Display them
        $headers = ['Email', 'Firstname', 'Lastname'];
        $this->table($headers, $accounts);
    }

}