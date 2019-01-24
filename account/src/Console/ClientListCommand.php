<?php

namespace Trax\Account\Console;

class ClientListCommand extends ClientCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'client:list';

    /**
     * The console command description.
     */
    protected $description = 'List clients';

    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('');
        
        // Get the accounts
        $accounts = $this->store->get()->map(function ($account) {
            return array(
                $account->username,
                $account->password,
            );
        });

        // Display them
        $headers = ['Username', 'Password'];
        $this->table($headers, $accounts);
    }

}