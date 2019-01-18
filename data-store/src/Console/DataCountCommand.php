<?php

namespace Trax\DataStore\Console;

class DataCountCommand extends DataCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'data:count';

    /**
     * The console command description.
     */
    protected $description = 'Count data records.';

    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('');
        $nb = $this->store->count();
        $this->line('Number of data records: '.$nb);
    }

}