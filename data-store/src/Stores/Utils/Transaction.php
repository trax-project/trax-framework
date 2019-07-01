<?php

namespace Trax\DataStore\Utils;

use DB;
use Closure;

trait Transaction 
{

    /**
     * Use a DB transaction except if there is no relational DB configured.
     */
    function transaction(Closure $callback, $supported = null)
    {
        if (isset($supported)) {

            // No store defined
            if ($supported) {
                return DB::transaction($callback);
            } else {
                return $callback($this);
            }

        } else {

            // Store defined
            if ($this->store->driver() == 'mongo') {
                return $callback($this);
            } else {
                return DB::transaction($callback);
            }
        }
    }


}
