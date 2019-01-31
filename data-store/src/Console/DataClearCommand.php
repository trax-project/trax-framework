<?php

namespace Trax\DataStore\Console;

class DataClearCommand extends DataCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'data:clear';

    /**
     * The console command description.
     */
    protected $description = 'Delete all data records.';

    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('');
        if (!$this->confirm('Do you really want to delete all data records?')) return;
        if (!$this->confirm('Are you really sure?')) return;
        $nb = $this->store->clear();
        $this->line('All data records have been deleted.');
    }

}