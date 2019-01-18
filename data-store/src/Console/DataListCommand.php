<?php

namespace Trax\DataStore\Console;

class DataListCommand extends DataCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'data:list';

    /**
     * The console command description.
     */
    protected $description = 'List data ids.';

    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('');
        
        // Get the datas ids
        $ids = $this->store->get()->map(function ($record) {
            return $record->id;
        })->toArray();
        
        // Display it
        $ids = implode(',', $ids);
        $this->line('Data IDs: '.$ids);
    }

}