<?php

namespace Trax\Account\Console;

class ClientDeleteCommand extends ClientCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'client:delete {username}';

    /**
     * The console command description.
     */
    protected $description = 'Delete an existing client';

    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('');

        // Get the account
        try {
            $account = $this->store->findBy('username', $this->argument('username'));
        } catch (\Exception $e) {
            return $this->error("The specified client does not exist.");
        }

        // Confirm
        if (!$this->confirm('Do you really want to delete this client?')) return;
        
        // Delete it
        try {
            $this->store->delete($account->id);
        } catch (\Exception $e) {
            $this->line('Account deletion failed!');
            return;
        }

        // Display them
        $this->line('Deleted client: '.$this->argument('username'));
    }

}