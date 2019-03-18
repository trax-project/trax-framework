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
        if (count($names) == 1) {
            if (isset($prefix)) return '`' . $prefix . '`.`' . $name . '`';
            if ($this->join) return '`' . $this->table . '`.`' . $name . '`';
            return '`' . $name . '`';
        }
        $column = array_shift($names);
        $name = implode('.', $names);
        $column = '`' . $column . '`';
        if (isset($prefix)) $column = '`' . $prefix . '`.' . $column;
        else if ($this->join) $column = '`' . $this->table . '`.' . $column;
        $prop = 'JSON_EXTRACT('.$column.', \'$.'.$name.'\')';
        return $prop;
    }

   
}
