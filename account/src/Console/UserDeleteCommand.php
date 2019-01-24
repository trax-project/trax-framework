<?php

namespace Trax\Account\Console;

class UserDeleteCommand extends UserCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'user:delete {identifier}';

    /**
     * The console command description.
     */
    protected $description = 'Delete an existing user';

    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('');

        // Get the account
        try {
            if (config('trax-account.auth.username')) {
                $account = $this->store->findBy('username', $this->argument('identifier'));
            } else {
                $account = $this->store->findBy('email', $this->argument('identifier'));
            }
        } catch (\Exception $e) {
            return $this->error("The specified user does not exist.");
        }

        // Confirm
        if (!$this->confirm('Do you really want to delete this user?')) return;
        
        // Delete it
        $this->store->delete($account->id);

        // Display them
        $this->line('Deleted user: '.$this->argument('identifier'));
    }

}