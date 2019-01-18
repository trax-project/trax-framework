<?php

namespace Trax\DataStore\Console;

class DataDeleteCommand extends DataCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'data:delete {id}';

    /**
     * The console command description.
     */
    protected $description = 'Delete a data record.';

    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('');

        // Check input data
        $id = $this->argument('id');
        if (!intval($id)) return $this->error("The ID must be an integer.");

        // Get the data
        try {
            $record = $this->store->find($id);
        } catch (\Exception $e) {

            // Not found
            return $this->error("The specified data does not exist.");
        }

        // Confirm
        if (!$this->confirm('Do you really want to delete this data record?')) return;
            
        // Delete it
        $this->store->delete($record->id);

        // Display result
        $this->line('Data deleted.');
    }

}