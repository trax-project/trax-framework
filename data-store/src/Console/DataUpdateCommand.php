<?php

namespace Trax\DataStore\Console;

class DataUpdateCommand extends DataCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'data:update {id} {data}';

    /**
     * The console command description.
     */
    protected $description = '
        Create a new data record. 
        Format: "prop1:val1;prop2;val2".
        Unicode chars are not supported.
    ';

    
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

        // Check ID
        $id = $this->argument('id');
        if (!intval($id)) return $this->error("The ID must be an integer.");

        // Update it
        $record = $this->store->update($id, $data, ['format'=>'object']);
        
        // Display it
        $this->line('Data updated: ');
        dd($record);
    }

}