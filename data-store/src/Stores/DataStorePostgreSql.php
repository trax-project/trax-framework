<?php

namespace Trax\DataStore\Stores;

trait DataStorePostgreSql
{

    /**
     * Prepare a data before recording it: post-processing.
     * Implement virtual columns as they are not supported by PostgreSQL.
     */
    protected function recordInput($record, $model = null)
    {
        foreach($this->virtualColumns as $source => $dest) {
            $props = explode('.', $source);
            $val = $record;
            foreach($props as $prop) {
                $val = $val[$prop];
            }
            $record[$dest] = $val;
        }
        return parent::recordInput($record, $model);
    }
    
    /**
     * Where implementation to work with JSON data and Boolean.
     */
    protected function whereBool($builder, $prop, $val)
    {
        return $builder->whereRaw('('.$this->normalizedDataPropRaw($prop).')::boolean = '.($val ? 'TRUE' : 'FALSE'));
    }

    /**
     * Where implementation to work with JSON data and Boolean.
     */
    protected function orWhereBool($builder, $prop, $val)
    {
        return $builder->orWhereRaw('('.$this->normalizedDataPropRaw($prop).')::boolean = '.($val ? 'TRUE' : 'FALSE'));
    }

    /**
     * Where implementation to work with data arrays.
     */
    protected function whereInJsonArray($builder, $key, $val)
    {
        $parts = explode('[*]', $key);
        $searchPath = $this->normalizedDataPath($parts[0]);
        $searchObject = empty($parts[1]) ? $val : $this->normalizedDataObject($parts[1], $val);
        $extract = "data #> ".$searchPath;
        $raw1 = "(".$searchPath.")::jsonb @> '".$searchObject."'::jsonb";
        $raw2 = "(".$searchPath.")::jsonb @> '[".$searchObject."]'::jsonb";
        return $builder->whereRaw($raw1.' OR '. $raw2);
    }

    /**
     * Get normalized data property name (with dollar).
     * Used with LIKE & =
     * data.a.b  >  "data #> '{a,b}'"
     */
    protected function normalizedDataPath($name)
    {
        $names = explode('.', $name);
        array_shift($names);
        $name = implode(',', $names);
        $prop = "data #> '{".$name."}'";
        return $prop;
    }
   
    /**
     * Get normalized data property name (with dollar).
     * Used with LIKE & =
     * data.a.b  >  'data' -> 'a' ->> 'b'
     */
    protected function normalizedDataPropRaw($name, $returnJson = false)
    {
        $names = explode('.', $name);
        if (count($names) == 1) return '`' . $name . '`';
        $column = array_shift($names);
        $last = array_pop($names);
        $names = array_map(function($item) {
            return "'".$item."'";
        }, $names);
        $name = implode(' -> ', $names);
        $lastOp = $returnJson ? '->' : '->>';
        $name .= " ".$lastOp." '".$last."'"; 
        if (count($names) > 0) $name = " -> ".$name;
        $prop = '"'.$column.'"'.$name;
        return $prop;
    }
   
}
