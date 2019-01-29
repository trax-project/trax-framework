<?php

namespace Trax\DataStore\Stores;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use Trax\DataStore\Exceptions\DataInputException;
use Trax\DataStore\Exceptions\DatabaseException;
use Trax\DataStore\Exceptions\NotFoundException;

class DataStoreDatabase implements DataStoreInterface
{
    /**
     * The application instance.
     */
    protected $app;
    
    /**
     * The DB driver.
     */
    protected $driver = 'database';
    
    /**
     * The DB builder.
     */
    protected $builder;

    /**
     * The name of the database connection.
     */
    protected $connection;

    /**
     * The name of the data table.
     */
    protected $table;

    /**
     * The name of the data model.
     */
    protected $model;

    /**
     * Options.
     */
    protected $options = [];

    /**
     * Relations.
     */
    protected $relations = [];

    /**
     * Relations that must be resolved.
     */
    protected $withRelations = [];

    /**
     * Select used by the Get request.
     */
    protected $select = ['*'];

    /**
     * To know if dates are automatically managed.
     */
    protected $autoDates = false;

    /**
     * Output format when storing or updating ('id' or 'object').
     */
    protected $outputFormat = 'id';

    /**
     * The attributes that should be visible.
     */
    protected $visible = ['id', 'data', 'created_at', 'updated_at'];

    /**
     * The attributes that should never be changed.
     */
    protected $protected = ['id', 'created_at', 'updated_at'];
    
    /**
     * Columns.
     */
    protected $columns = array();
    
    /**
     * Virtual columns supported.
     */
    protected $virtualColumnsSupported = true;
    
    /**
     * Virtual columns.
     */
    protected $virtualColumns = [];

    /**
     * Default values.
     */
    protected $defaultValues = [];

    /**
     * Props used for the global search.
     */
    protected $globalSearchScopes = array();

    /**
     * Default ordering settings.
     */
    protected $defaultOrderingCol = 'id';
    protected $defaultOrderingDir = 'asc';


    /**
     * Create a new data API instance.
     */
    public function __construct($app, $connection, $table, $model)
    {
        $this->app = $app;
        $this->connection  = $connection;
        $this->table = $table;
        $this->model = $model;
        $this->initDefinition();
        $this->initStore();
        $this->clearBuilder();
        $this->prepareVirtualColumns();
    }

    /**
     * Get the name of the model.
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * Get data entries.
     */
    public function get($args = array(), $options = array())
    {
        // Default ordering
        if (!isset($args['order-by'])) {
            $args['order-by'] = $this->defaultOrderingCol;
            $args['order-dir'] = $this->defaultOrderingDir;
        }

        // Init
        $args = $this->normalizeArgs($args);
        $insertCount = isset($options['count']) && $options['count'];
        $insertFilteredCount = $insertCount || (isset($options['filteredCount']) && $options['filteredCount']);
        $insertTotalCount = $insertCount || (isset($options['totalCount']) && $options['totalCount']);

        // Start from the model
        $this->clearBuilder();
        $builder = $this->builder;
        if ($insertTotalCount) $totalCount = $this->fastCount();
        
        // Filter data
        $filtered = $builder;
        if (isset($args['since'])) {
            $filtered = $this->since($filtered, $args['since']);
        }
        if (isset($args['until'])) {
            $filtered = $this->until($filtered, $args['until']);
        }
        if (isset($args['search'])) {
            $filtered = $this->search($filtered, $args['search']);
        }
        if (isset($args['global-search'])) {
            $filtered = $this->globalSearch($filtered, $args['global-search']);
        }
        if ($insertFilteredCount) {
            $filteredCount = $filtered->count();
            if (!$insertTotalCount) $totalCount = $filteredCount;
        }
        
        // With relations
        $filtered = $this->withRelations($filtered, $options);

        // Order data
        $ordered = $filtered;
        if (isset($args['order-by']) && $args['order-by'] != 'data') {
            $dir = isset($args['order-dir']) ? $args['order-dir'] : 'asc';
            $ordered = $this->orderBy($ordered, $args['order-by'], $dir);
        }

//echo($ordered->toSql());
//print_r($ordered->getBindings());
//die;

        // Paginate data
        $paginated = $ordered;
        if (isset($args['limit']) && $args['limit'] > 0) $paginated = $paginated->limit($args['limit']);
        if (isset($args['offset'])) $paginated = $paginated->offset($args['offset']);

        // It time to get them
        $result = $paginated->get($this->select);

        // Prepare output
        $result->transform(function ($item) use ($options) {
            return $this->modelOutput($item, $options);
        });

        // Return result
        if ($insertCount || $insertFilteredCount) {
            
            // Return Object with count information
            return (object)array('data'=> $result, 'total_count'=>$totalCount, 'filtered_count'=>$filteredCount);
        } else {
            
            // Return an array of data entries
            return $result;
        }
    }

    /**
     * Store a data.
     */
    public function store($data, $options = array())
    {        
        // Prepare data
        $record = $this->dataInput($data, $options);

        // Insert data entry
        $this->clearBuilder();
        try {
            $id = $this->builder->insertGetId($record);
        } catch (\Exception $e) {
            throw new DatabaseException('insertGetId exception.');
        }
        
        // Result
        if (!$id)
            throw new DatabaseException('Storing error.');            // Error
        if ((isset($options['format']) && $options['format'] == 'id') 
            || (!isset($options['format']) && $this->outputFormat == 'id')) {
            //Cache::increment('table.'.$this->table.'.count');
            return $this->idOutput($id, $record);                               // Return ID
        }
        $model = $this->find($id);                                              // Return Object
        if (!$model)
            throw new DatabaseException('Storing error.');            // Error
        //Cache::increment('table.'.$this->table.'.count');
        return $this->modelOutput($model, $options);
    }
    
    /**
     * Update a data.
     */
    public function update($id, $data, $options = array())
    {
        // Get data entry
        if (!$model = $this->find($id)) 
            throw new NotFoundException('Record to be updated does not exist.');
        
        // Prepare data
        $record = $this->dataInput($data, $options, $model);
    
        // Update data entry
        $this->clearBuilder();
        try {
            $this->builder->where('id', $id)->update($record);
        } catch (\Exception $e) {
            throw new DatabaseException('Update exception.');
        }
        
        // Return result
        if ((isset($options['format']) && $options['format'] == 'id')
            || (!isset($options['format']) && $this->outputFormat == 'id')) {
            return $this->idOutput($id, $record);                           // Return ID
        }
        $model = $this->find($id);                                          // Return Object
        if (!$model)
            throw new DatabaseException('Updating error.');       // Error
        return $this->modelOutput($model, $options);
    }
    
    /**
     * Find a data entry, given its id.
     */
    public function find($id, $options = array())
    {
        $this->clearBuilder();
        $model = $this->withRelations($this->builder, $options);
        $model = $model->where('id', $id)->first();
        if (!$model)
            throw new NotFoundException('The requested record does not exist.');
        return $this->modelOutput($model, $options);
    }
    
    /**
     * Find a data entry, given a unique field.
     */
    public function findBy($field, $value, $options = array())
    {
        $this->clearBuilder();
        $model = $this->withRelations($this->builder, $options);
        $model = $this->searchTermIn($model, $field, $value, true)->first();
        if (!$model)
            throw new NotFoundException('The requested record does not exist.');
        return $this->modelOutput($model, $options);
    }
    
    /**
     * Delete a data entry, given its id.
     */
    public function delete($id)
    {
        // Get data entry
        try {
            $model = $this->find($id);
        } catch(\Exception $e) {
            throw new NotFoundException('Record to be deleted does not exist.');
        }
        
        // Delete it
        $this->clearBuilder();
        if (!$this->builder->where('id', $id)->delete())
            throw new DatabaseException('Deleting error.');       
        
        //Cache::decrement('table.'.$this->table.'.count');
        return true;
    }
    
    /**
     * Delete all the data entries.
     */
    public function clear()
    {
        $this->clearBuilder();
        $this->builder->truncate();
        //Cache::forever('table.'.$this->table.'.count', 0);
    }

    /**
     * Count the data entries.
     */
    public function count($args = [])
    {
        $this->clearBuilder();
        $builder = $this->builder;

        // Filter data
        $filtered = $builder;
        if (isset($args['search'])) {
            $filtered = $this->search($filtered, $args['search']);
        }
        return $filtered->count();
    }
    
    /**
     * Count all the data entries faster.
     */
    public function fastCount()
    {
        return $this->count();
        /*
        $count = Cache::get('table.'.$this->table.'.count');
        if (is_null($count) || rand(0, 100) == 0) {
            Cache::forever('table.'.$this->table.'.count', $this->count());
        }
        return $count;
        */
    }


    // =========================================== PROTECTED ==================================== //


    /**
     * Init store props.
     */
    private function initDefinition()
    {
        $model = new $this->model();
        if (!method_exists($model, 'definition')) return;
        $definition = $model->definition();
        if (!$definition) return;
        $this->visible = $definition->visible;
        $this->protected = array_merge($this->protected, $definition->protected);
        $this->columns = $definition->columns;
        $this->options = $definition->options;
        $this->relations = $definition->relations;
        $this->virtualColumns = $definition->virtualColumns;
        $this->defaultValues = $definition->defaultValues;
    }

    /**
     * Init store hook. May be overridden.
     */
    protected function initStore()
    {
    }

    /**
     * Clear the builder.
     */
    protected function clearBuilder()
    {
        $this->builder = $this->app->make('db')->connection($this->connection)->table($this->table);
        if (!empty($this->visible)) $this->builder = $this->builder->select($this->visible);
    }
   
    /**
     * Prepare virtual columns and their consequences.
     */
    protected function prepareVirtualColumns() 
    {
        if (!$this->virtualColumnsSupported) return;
        $globalSearchScopes = [];
        foreach($this->globalSearchScopes as $column) {
            if (isset($virtualColumns[$column])) $globalSearchScopes[] = $virtualColumns[$column];
            else $globalSearchScopes[] = $column;
        }
        $this->globalSearchScopes = $globalSearchScopes;
    }

    /**
     * Normalize arguments.
     */
    protected function normalizeArgs($args)
    {
        if (isset($args['limit'])) $args['limit'] = intval($args['limit']);
        if (isset($args['offset'])) $args['offset'] = intval($args['offset']);
        return $args;
    }
   
    /**
     * Get normalized date for requests.
     */
    protected function normalizedDate($isoDateString)
    {
        return date('Y-m-d H:i:s', strtotime($isoDateString));
    }

    /**
     * Get normalized data property name (simple version).
     * Used with Eloquent requests.
     * data.a.b  >  data->a->b
     */
    protected function normalizedDataProp($name)
    {
        return str_replace('.', '->', $name);
    }

    /**
     * Get normalized data property name (with dollar).
     * Used with SQL raw request.
     * data.a.b  >  data->"$.a.b"
     */
    protected function normalizedDataPropRaw($name)
    {
        $names = explode('.', $name);
        if (count($names) == 1) return '`' . $name . '`';
        $column = array_shift($names);
        $name = implode('.', $names);
        $prop = $column.'->"$.'.$name.'"';
        return $prop;
    }

    /**
     * Get normalized data property path.
     * Used with JSON_CONTAINS
     * data.a.b  >  $.a.b
     */
    protected function normalizedDataPath($name)
    {
        $searchPath = explode('.', $name);
        array_shift($searchPath);
        $searchPath = '$.'.implode('.', $searchPath);
        return $searchPath;
    }

    /**
     * Get normalized data property object.
     * Used with JSON_CONTAINS
     * .c.d  >  {"c":{"d":"val"}}
     */
    protected function normalizedDataObject($name, $val)
    {
        $searchProp = explode('.', $name);
        array_shift($searchProp);
        $searchProp = array_reverse($searchProp);
        $searchObject = '{"'.$searchProp[0].'" : "'.$val.'"}';
        array_shift($searchProp);
        foreach($searchProp as $prop) {
            $searchObject = '{"'.$prop.'" : '.$searchObject.'}';
        }
        return $searchObject;
    }

    /**
     * Get normalized JSON data (associative array).
     */
    protected function normalizedJsonData($data)
    {
        if (is_string($data)) {
            $data = json_decode($data, true);
            if (!$data) throw new DataInputException('Invalid JSON string.');
        } else {
            // Check if it is an object and if it contains objects
            if ($this->hasObject($data)) $data = json_decode(json_encode($data), true);
        }
        return $data;
    }

    /**
     * Prepare a data before recording it.
     */
    protected function dataInput($data, $options, $model = null)
    {
        // Normalize input data
        $data = $this->normalizedJsonData($data);
        
        // Remove protected props
        foreach ($this->protected as $prop) {
            if (isset($data[$prop])) unset($data[$prop]);
        }

        // Default value
        if (is_null($model)) $data = $this->setDefaultValues($data);
        
        // Pre-processing
        $data = $this->dataInputPre($data, $options, $model);
        
        // Basic process
        if (empty($this->columns)) {

            // Prepare data
            $record = ['data' => $data];

            // Dates
            $record = $this->addDates($record, $model);

            return $this->recordInput($record, $model);
        }
        
        // Rollout columns
        $record = $this->rollupColumns($data);
        
        // Dates
        $record = $this->addDates($record, $model);
        
        return $this->recordInput($record, $model);
    }

    /**
     * Set default values.
     */
    protected function setDefaultValues($data)
    {
        foreach($this->defaultValues as $key => $def) {
            if (!isset($data[$key]) || empty($data[$key])) {

                if ($def['type'] == 'function') {
                    $data[$key] = $def['default']();

                } else if ($def['type'] == 'prop') {
                    $data[$key] = $data[$def['default']];

                } else if ($def['type'] == 'config') {
                    $data[$key] = config($def['default']);

                } else if ($def['type'] == 'value') {
                    $data[$key] = $def['default'];
                }
            }
        }
        return $data;
    }

    /**
     * Prepare a data before recording it: pre-processing.
     */
    protected function dataInputPre($data, $options, $model = null)
    {
        return $data;
    }
    
    /**
     * Prepare a data before recording it: post-processing.
     */
    protected function recordInput($record, $model = null)
    {
        // Transform the associative array into string
        $record['data'] = json_encode($record['data'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return $record;
    }

    /**
     * Implement Eloquent 'with'.
     */
    protected function withRelations($builder, $options)
    {
        $this->withRelations = isset($options['with']) ? $options['with'] : [];
        return $builder;
    }

    /**
     * Get the data entry ID to be returned.
     */
    protected function idOutput($id, $record)
    {
        return $id;
    }

    /**
     * Prepare a data entry before returning it.
     */
    protected function modelOutput($model, $options = [])
    {
        // Return Object data from String
        if (is_string($model->data)) $model->data = json_decode($model->data);
        
        // Return Object data from Array
        else $model->data = json_decode(json_encode($model->data));
        
        // Flat model
        if (isset($options['flat']) && $options['flat']) $model = $this->rolldownColumns($model);

        // Relations output
        $model = $this->withRelationsOutput($model, $options);

        return $model;
    }

    /**
     * Implement Eloquent 'with'.
     */
    protected function withRelationsOutput($model, $options = [])
    {
        foreach ($this->withRelations as $relation) {
            if (isset($model->$relation) && isset($this->relations[$relation])) {
                if ($this->relations[$relation]['type'] == 'multiple') {
                    
                    // Multiple relation
                    $model->$relation->transform(function ($item) use ($options) {
                        return $this->modelOutput($item, $options);
                    });
                } else {

                    // Single relation
                    $model->$relation = $this->modelOutput($model->$relation, $options);
                }
            } else if (isset($this->options[$relation])) {

                // Options
                $def = $this->options[$relation];
                $optionsObject = traxOptions($def['model']);
                $code = $this->resolveProp($model, $def['key']);
                if (is_null($code)) continue;
                try {
                    $option = $optionsObject->find($code);
                    $model->$relation = $option;
                } catch (\Exception $e) {
                }
            }
        }
        return $model;
    } 

    /**
     * Filtering by date: since.
     */
    protected function since($builder, $isoDateString, $prop = 'created_at')
    {
        return $this->where($builder, $prop, $this->normalizedDate($isoDateString), '>');
    }
    
    /**
     * Filtering by date: until.
     */
    protected function until($builder, $isoDateString, $prop = 'created_at')
    {
        return $this->where($builder, $prop, $this->normalizedDate($isoDateString), '<=');
    }
    
    /**
     * Ordering.
     */
    protected function orderBy($builder, $by, $dir)
    {
        $byArray = explode('.', $by);
        $column = array_shift($byArray);
        if (!isset($this->relations[$column])) {

            // Standard order by 
            return $builder->orderByRaw($this->normalizedDataPropRaw($by) . ' ' . $dir);

        } else if ($this->relations[$column]['type'] == 'single') {

            // Order by a "belongsTo" relation
            $by = implode('.', $byArray);
            $table = $this->relations[$column]['table'];
            $this->select = [$this->table . '.*'];
            $builder = $builder->join($table, $table . '.id', '=', $this->table . '.' . $column . '_id');
            $builder = $builder->orderByRaw($table . '.' . $this->normalizedDataPropRaw($by) . ' ' . $dir);
            return $builder;

        } else {

            // Order by a "hasMany" relation: not supported
            return $builder;
        }
    }

    /**
     * Where implementation to work with JSON data.
     */
    protected function where($builder, $prop, $val, $op = '=')
    {
        if ($op == '=') return $this->searchTermIn($builder, $prop, $val, true, false); // Try to solve sensitivity issues
        else if ($op == 'LIKE') return $this->searchTermIn($builder, $prop, $val, false, false); // Try to solve sensitivity issues
        else if ($op == 'BOOL') return $this->whereBool($builder, $prop, $val);
        else return $this->whereNum($builder, $prop, $val, $op);
    }

    /**
     * Or where implementation to work with JSON data.
     */
    protected function orWhere($builder, $prop, $val, $op = '=')
    {
        if ($op == '=') return $this->searchTermIn($builder, $prop, $val, true, true); // Try to solve sensitivity issues
        else if ($op == 'LIKE') return $this->searchTermIn($builder, $prop, $val, false, true); // Try to solve sensitivity issues
        else if ($op == 'BOOL') return $this->orWhereBool($builder, $prop, $val);
        return $this->orWhereNum($builder, $prop, $val, $op);
    }

    /**
     * Where implementation to work with JSON data and Numbers.
     */
    protected function whereNum($builder, $prop, $val, $op = '=')
    {
        return $builder->whereRaw($this->normalizedDataPropRaw($prop).' '.$op.' \''.$val.'\'');
    }

    /**
     * Where implementation to work with JSON data and Numbers.
     */
    protected function orWhereNum($builder, $prop, $val, $op = '=')
    {
        return $builder->orWhereRaw($this->normalizedDataPropRaw($prop).' '.$op.' \''.$val.'\'');
    }

    /**
     * Where implementation to work with JSON data and Boolean.
     */
    protected function whereBool($builder, $prop, $val)
    {
        return $builder->whereRaw($this->normalizedDataPropRaw($prop).' = '.($val ? 'TRUE' : 'FALSE'));
    }

    /**
     * Where implementation to work with JSON data and Boolean.
     */
    protected function orWhereBool($builder, $prop, $val)
    {
        return $builder->orWhereRaw($this->normalizedDataPropRaw($prop).' = '.($val ? 'TRUE' : 'FALSE'));
    }

    /**
     * WhereNull implementation to work with JSON data.
     */
    protected function whereNull($builder, $prop)
    {
        return $builder->whereRaw($this->normalizedDataPropRaw($prop).' IS NULL');
    }

    /**
     * Or whereNull implementation to work with JSON data.
     */
    protected function orWhereNull($builder, $prop)
    {
        return $builder->orWhereRaw($this->normalizedDataPropRaw($prop).' IS NULL');
    }

    /**
     * WhereNotNull implementation to work with JSON data.
     */
    protected function whereNotNull($builder, $prop)
    {
        return $this->where($builder, $prop, '%', 'LIKE');
    }

    /**
     * Or WhereNotNull implementation to work with JSON data.
     */
    protected function orWhereNotNull($builder, $prop)
    {
        return $this->orWhere($builder, $prop, '%', 'LIKE');
    }

    /**
     * Where implementation to work with data arrays.
     */
    protected function whereInJsonArray($builder, $key, $val)
    {
        $parts = explode('[*]', $key);
        $searchPath = $this->normalizedDataPath($parts[0]);
        $searchObject = empty($parts[1]) ? $val : $this->normalizedDataObject($parts[1], $val);
        return $builder->whereRaw("JSON_CONTAINS(data, '" . $searchObject . "', '" . $searchPath . "')");
    }

    /**
     * Searching.
     */
    protected function search($builder, $items)
    {
        // AND iteration
        foreach($items as $key => $value) {
            if (!is_array($value) && !is_object($value)) {
                
                // The KEY is the prop
                if (strpos($key, '[*]') !== false) {
                    $builder = $this->whereInJsonArray($builder, $key, $value);
                } else {
                    $builder = $this->where($builder, $key, $value);
                }

            } else if (is_object($value)) {
                
                // [*] currently not supported in key
                if (!is_string($key)) $key = $value->key;

                if ($value->operator == 'IN') {

                    // IN operator
                    // 'Where In' does not work with multiple values!
                    //$builder = $builder->whereIn($this->normalizedDataProp($key), $value->value);
                    if (empty($value->value)) {

                        // Force to return nothing
                        $builder = $this->where($builder, $key, 'Will never find this');

                    } else {
                        $builder = $builder->where(function ($query) use ($key, $value) {
                            foreach ($value->value as $candidate) {
                                $query = $this->orWhere($query, $key, $candidate);
                            }
                        });  
                    }

                } else if ($value->operator == 'EQUAL_OR_NULL') {

                    // EQUAL_OR_NULL operator
                    $builder = $builder->where(function($query) use ($key, $value) {
                        $query = $this->orWhere($query, $key, $value->value);
                        $query = $this->orWhereNull($query, $key);
                    });

                } else if ($value->operator == 'EXISTS' || $value->operator == 'NOT_NULL') {

                    // EXISTS operator
                    $builder = $this->whereNotNull($builder, $key);

                } else if ($value->operator == 'NOT_EXISTS' || $value->operator === 'NULL') {

                    // NOT_EXISTS operator
                    $builder = $this->whereNull($builder, $key);

                } else {

                    // Other operators
                    $op2 = isset($value->operator2) ? $value->operator2 : null;
                    $builder = $this->where($builder, $key, $value->value, $value->operator, $op2);
                }
                
            } else $builder = $builder->where(function ($query) use ($value) {
                
                // OR conditions
                foreach($value as $key2 => $value2) {
                    $query = $query->orWhere(function ($query2) use ($key2, $value2) {

                        // Single condition
                        if (!is_array($value2)) $value2 = [$key2 => $value2];

                        // AND iteration
                        $query2 = $this->search($query2, $value2);
                    });
                }
            });
        }
        return $builder;
    }
    
    /**
     * Global search implementation.
     */
    protected function globalSearch($builder, $searchTerm)
    {
        // No search
        if (empty($this->globalSearchScopes)) return $builder;
        
        // Search many terms (OR)
        $terms = array_values(array_filter(explode(' ', $searchTerm)));
        
        // Nothing to search
        if (count($terms) == 0) return $builder;

        // Group global search items
        $builder = $builder->where(function ($query) use ($terms) {
            
            // First term
            $query = $this->globalSearchTerm($query, $terms[0]);
            
            // Next terms
            for($i = 1; $i < count($terms); $i++) {
                $query = $this->globalSearchTerm($query, $terms[$i], true);
            }
        });
        
        return $builder;
    }
    
    /**
     * Global search for a single term.
     */
    protected function globalSearchTerm($builder, $searchTerm, $forceOr = false)
    {
        // First item
        $builder = $this->searchTermIn($builder, $this->globalSearchScopes[0], $searchTerm, false, $forceOr);
        
        // Next items
        for($i = 1; $i < count($this->globalSearchScopes); $i++) {
            $builder = $this->searchTermIn($builder, $this->globalSearchScopes[$i], $searchTerm, false, true);
        }
        
        return $builder;
    }
    
    /**
     * Search for a single term in a given prop.
     */
    protected function searchTermIn($builder, $prop, $searchTerm, $exact = false, $or = false) 
    {
        if (count(explode('.', $prop)) > 1) {            
            $prop = $this->normalizedDataPropRaw($prop);

            // Case insensitive: only works with 'like'
            if (!$exact) {
                $prop = 'LOWER('.$prop.')';
                $searchTerm = strtolower($searchTerm);
            }
        }

        // Prepare raw SQL
        if ($exact) {
            $sql = $prop.' = ?';
        } else {
            $sql = $prop.' LIKE ?';
            $searchTerm = '%'.$searchTerm.'%';
        }
        $arr = array($searchTerm);

        // Request
        if (!$or) $builder = $builder->whereRaw($sql, $arr);
        else $builder = $builder->orWhereRaw($sql, $arr);
        return $builder;
    }

    /**
     * Returns a database record, given an input array data.
     */
    protected function rollupColumns($data)
    {
        $res = array();
        foreach ($this->columns as $column) {
            if (array_key_exists($column, $data)) {
                $res[$column] = isset($data[$column]) ? $data[$column] : null;
                unset($data[$column]);
            }
        }
        $res['data'] = $data;
        return $res;
    }

    /**
     * Returns an input data, given a database record (object).
     */
    protected function rolldownColumns($model)
    {
        $res = $model->data;
        if (is_array($res) && empty($res)) $res = (object)[];
        foreach ($this->columns as $column) {
            $res->$column = isset($model->$column) ? $model->$column : null;
        }
        $res->id = $model->id;
        return $res;
    }    

    /**
     * Add dates to input data when necessary.
     */
    protected function addDates($record, $model = null)
    {
        if ($this->autoDates) return $record;
        $record['updated_at'] = traxNow();
        if (is_null($model)) $record['created_at'] = traxNow();
        return $record;
    }    
    
    /**
     * Check if the data contains objects, or if it is a pure array.
     */
    protected function hasObject($data)
    {
        if (is_object($data)) return true;
        if (is_array($data)) {
            foreach($data as $item) {
                if ($this->hasObject($item)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Get model prop given its dotted name or an array of props to chain.
     */
    protected function resolveProp($model, $target)
    {
        if (is_string($target)) $target = explode('.', $target);
        if (empty($target)) return $model;
        $first = array_shift($target);
        if (!isset($model->$first)) return null;
        return $this->resolveProp($model->$first, $target);
    }


}
