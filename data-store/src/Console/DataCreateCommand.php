<?php

namespace Trax\DataStore\Console;

class DataCreateCommand extends DataCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'data:create {data}';

    /**
     * The console command description.
     */
    protected $description = 'Create a new data record.';

    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('');

        // Get input data
        $data = $this->formatObject($this->argument('data'));

        // Check data
        $check = json_encode($data);
        if (!$check) {
            $this->error('Data format is not correct.');
            $this->error('Format: "prop1:val1;prop2;val2".');
            $this->error('Unicode chars are not supported.');
            return;
        }

        // Store it
        $record = $this->store->store($data, ['format'=>'object']);
        
        // Display it
        $this->line('Data created: ');
        dd($record);
    }

}