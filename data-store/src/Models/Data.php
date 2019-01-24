<?php

namespace Trax\DataStore\Models;

traxCreateModelSwitchClass('Trax\DataStore\Models', 'trax-data-store', 'Data');

class Data extends DataModel
{
    /**
     * The table associated with the model.
     */
    protected $table = 'trax_datastore_data';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['data'];
    
}
