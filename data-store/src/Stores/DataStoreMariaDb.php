<?php

namespace Trax\DataStore\Stores;

trait DataStoreMariaDb
{
    /**
     * Get normalized data property name (with dollar).
     * Used with LIKE & =
     * data.a.b  >  JSON_EXTRACT(`data`, \'$.a.b\')
     */
    protected function normalizedDataPropRaw($name)
    {
        $names = explode('.', $name);
        if (count($names) == 1) return '`' . $name . '`';
        $column = array_shift($names);
        $name = implode('.', $names);
        $prop = 'JSON_EXTRACT(`'.$column.'`, \'$.'.$name.'\')';
        return $prop;
    }

   
}
