<?php

namespace Trax\DataStore\Console;

class DataShowCommand extends DataCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'data:show {id}';

    /**
     * The console command description.
     */
    protected $description = 'Show a data record.';

    
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

        // Display it
        dd($record);
    }

}