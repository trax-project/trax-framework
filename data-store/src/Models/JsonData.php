<?php

namespace Trax\DataStore\Models;

trait JsonData
{

    /**
     * Get data as JSON object.
     */
    public function getDataAttribute($value)
    {
        if (is_string($value)) return json_decode($value);
        if (is_array($value)) return json_decode(json_encode($value));
        return $value;
    }

}
