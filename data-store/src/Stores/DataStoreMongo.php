<?php

namespace Trax\DataStore\Stores;

class DataStoreMongo extends DataStoreEloquent
{
    /**
     * The DB driver.
     */
    protected $driver = 'mongo';
    
    /**
     * Virtual columns supported.
     */
    protected $virtualColumnsSupported = false;
    
    
    /**
     * Get normalized date for requests.
     */
    protected function normalizedDate($isoDateString)
    {
        return new \DateTime($isoDateString);
    }

    /**
     * Get normalized data property name (simple version).
     * Used with orderBy & where.
     * data.a.b  >  data.a.b
     */
    protected function normalizedDataProp($name)
    {
        if ($name == 'id') return '_id';
        return $name;
    }

    /**
     * Prepare a data before recording it: pre-processing.
     */
    protected function dataInputPre($data, $options, $model = null)
    {
        // Escape '.' and '$' chars from keys
        $data = $this->escapeUnauthorizedKeys($data);

        return $data;
    }
    
    /**
     * Prepare a data before recording it: post-processing.
     */
    /*
    Already overriden in EloquentStore, but still to be validated.
    protected function recordInput($record, $model = null)
    {
        // Transform the associative array into object
        $record['data'] = json_decode(json_encode($record['data']));
        return $record;
    }
    */ 

    /**
     * Prepare a data entry before returning it.
     */
    protected function modelOutput($model, $options = [])
    {
        $model = parent::modelOutput($model);
        
        // Restore '.' and '$' chars from keys
        $model->data = $this->restoreUnauthorizedKeys($model->data);
        
        // Change ID key
        $model->id = $model->_id;
        unset($model->_id);        

        // Flat model
        if (isset($options['flat']) && $options['flat']) $model = $this->flat($model);

        // Relations output
        $model = $this->withRelationsOutput($model, $options);

        return $model;
    }

    /**
     * Filtering by date: since.
     */
    protected function since($builder, $isoDateString, $prop = 'created_at')
    {
        return $builder->where($this->normalizedDataProp($prop), '>', $this->normalizedDate($isoDateString));
    }
    
    /**
     * Filtering by date: until.
     */
    protected function until($builder, $isoDateString, $prop = 'created_at')
    {
        return $builder->where($this->normalizedDataProp($prop), '<=', $this->normalizedDate($isoDateString));
    }
    
    /**
     * Ordering.
     */
    protected function orderBy($builder, $by, $dir)
    {
        return $builder->orderBy($this->normalizedDataProp($by), $dir);
    }

    /**
     * Where implementation to work with JSON data and Numbers.
     */
    protected function whereNum($builder, $prop, $val, $op = '=')
    {
        return $builder->where($this->normalizedDataProp($prop), $op, $val);
    }

    /**
     * Where implementation to work with JSON data and Numbers.
     */
    protected function orWhereNum($builder, $prop, $val, $op = '=')
    {
        return $builder->orWhere($this->normalizedDataProp($prop), $op, $val);
    }

    /**
     * Where implementation to work with JSON data and Boolean.
     */
    protected function whereBool($builder, $prop, $val)
    {
        return $builder->where($this->normalizedDataProp($prop), $val);
    }

    /**
     * Where implementation to work with JSON data and Boolean.
     */
    protected function orWhereBool($builder, $prop, $val)
    {
        return $builder->orWhere($this->normalizedDataProp($prop), $val);
    }

    /**
     * WhereNull implementation to work with JSON data.
     */
    protected function whereNull($builder, $prop)
    {
        return $builder->whereNull($this->normalizedDataProp($prop));
    }

    /**
     * Or where implementation to work with JSON data.
     */
    protected function orWhereNull($builder, $prop)
    {
        return $builder->orWhereNull($this->normalizedDataProp($prop));
    }

    /**
     * WhereNotNull implementation to work with JSON data.
     */
    protected function whereNotNull($builder, $prop)
    {
        return $builder->whereNotNull($this->normalizedDataProp($prop));
    }

    /**
     * Or WhereNotNull implementation to work with JSON data.
     */
    protected function orWhereNotNull($builder, $prop)
    {
        return $builder->orWhereNotNull($this->normalizedDataProp($prop));
    }

    /**
     * Where implementation to work with data arrays.
     */
    protected function whereInJsonArray($builder, $key, $val)
    {
        if (strpos($key, '[*].') !== false) {
            $parts = explode('[*].', $key);
        } else {
            $parts = explode('[*]', $key);
            array_pop($parts);
        }
        $key = implode('.', $parts);
        return $builder->where($key, $val);
    }

    /**
     * Search for a single term in a given prop.
     */
    protected function searchTermIn($builder, $prop, $searchTerm, $exact = false, $or = false) 
    {
        if ($exact) {
            if (!$or) $builder = $builder->where($prop, $searchTerm);
            else $builder = $builder->orWhere($prop, $searchTerm);
        } else {
            $searchTerm = '%'.$searchTerm.'%';
            if (!$or) $builder = $builder->where($prop, 'like', $searchTerm);
            else $builder = $builder->orWhere($prop, 'like', $searchTerm);
        }
        return $builder;
    }

    /**
     * Escape '.' and '$' chars from keys.
     */
    protected function escapeUnauthorizedKeys($dataArray, $context = null)
    {
        foreach ($dataArray as $key => $val) {
            
            // Recursivity
            if (is_array($val)) $val = $this->escapeUnauthorizedKeys($val, $key);
            
            // Key
            if (strpos($key, '.') !== false || strpos($key, '$') !== false) {
                $newKey = str_replace('.', '\u002E', $key);
                $newKey = str_replace('$', '\u0024', $newKey);
                $dataArray[$newKey] = $val;
                unset($dataArray[$key]);
            } else {
                $dataArray[$key] = $val;
            }
        }
        return $dataArray;
    }
    
    /**
     * Restore '.' and '$' chars from keys.
     */
    protected function restoreUnauthorizedKeys($dataObject, $context = null)
    {
        // Array recursivity
        if (is_array($dataObject)) {
            foreach($dataObject as $key => $val) {
                if (is_object($val)) {
                    $dataObject[$key] = $this->restoreUnauthorizedKeys($val);
                }
            }
            return $dataObject;
        }
        
        // Object
        $dataArray = get_object_vars($dataObject);
        foreach ($dataArray as $key => $val) {
            
            // Recursivity
            if (is_array($val) || is_object($val)) $val = $this->restoreUnauthorizedKeys($val, $key);
            
            // Key
            if (strpos($key, '\u002E') !== false || strpos($key, '\u0024') !== false) {
                $newKey = str_replace('\u002E', '.', $key);
                $newKey = str_replace('\u0024', '$', $newKey);
                $dataArray[$newKey] = $val;
                unset($dataArray[$key]);
            } else {
                $dataArray[$key] = $val;
            }
        }
        $res = json_decode(json_encode($dataArray));
        return $res;
    }
        
    
}
