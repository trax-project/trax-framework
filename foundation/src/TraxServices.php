<?php

namespace Trax\Foundation; 

use Trax\Foundation\Options\StandardStatus;

class TraxServices
{
    /**
     * The application instance.
     */
    protected $app;

    /**
     * Standard status.
     */
    protected $standardStatus;


    /**
     * Create services instance.
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->standardStatus = new StandardStatus();
    }

    /**
     * Get standard status.
     */
    public function standardStatus()
    {
        return $this->standardStatus;
    }


}
