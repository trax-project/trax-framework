<?php

namespace Trax\DataStore\Stores;

trait DataStoreMariaDb
{
    /**
     * Get normalized data property name (with dollar).
     * Used with LIKE & =
     * data.a.b  >  JSON_EXTRACT(`data`, \'$.a.b\')
     */
    protected function normalizedDataPropRaw($name, $prefix = null)
    {
        $names = explode('.', $name);
        if (count($names) == 1) return '`' . $name . '`';
        $column = array_shift($names);
        $name = implode('.', $names);
        $column = '`' . $column . '`';
        if (isset($prefix)) {
            $prefix = '`' . $prefix . '`';
            $column = $prefix . '.' . $column;
        }
        $prop = 'JSON_EXTRACT('.$column.', \'$.'.$name.'\')';
        return $prop;
    }

   
}
