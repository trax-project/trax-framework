<?php

namespace Trax\DataStore\Stores;

use Trax\DataStore\Exceptions\DatabaseException;
use Trax\DataStore\Exceptions\NotFoundException;
use Illuminate\Support\Facades\Cache;

class DataStoreEloquent extends DataStoreDatabase
{
    /**
     * The DB driver.
     */
    protected $driver = 'eloquent';
    
    /**
     * To know if date are automatically managed.
     */
    protected $autoDates = true;


    /**
     * Store a data.
     */
    public function store($data, $options = array())
    {
        // Prepare data
        $record = $this->dataInput($data, $options);

        // Return Object
        $this->clearBuilder();
        if ((isset($options['format']) && $options['format'] != 'id')
            || (!isset($options['format']) && $this->outputFormat != 'id')) {
            try {
                $model = $this->builder->create($record);
            } catch (\Exception $e) {
                throw new DatabaseException('Create exception.');
            }
            //Cache::increment('table.'.$this->table.'.count');
            return $this->modelOutput($model, $options);
        }

        // Return ID
        $entry = $this->newModel();
        foreach($record as $key => $val) {
            $entry->$key = $val;
        }
        try {
            $entry->save();
        } catch (\Exception $e) {
            throw new DatabaseException('Save exception.');
        }
        $entry = $this->modelOutput($entry, $options);  // Keep this because ID may be affected (e.g. Mongo)
        //Cache::increment('table.'.$this->table.'.count');
        return $this->idOutput($entry->id, $record);
    }
    
    /**
     * Update a data.
     */
    public function update($id, $data, $options = array())
    {
        // Get data entry
        $model = $this->builder->find($id);
        if (!$model)
            throw new NotFoundException('Record to be updated does not exist.');
        
        // Prepare data
        $record = $this->dataInput($data, $options, $model);

        // Update data entry
        $this->clearBuilder();
        $model->fill($record);
        try {
            $model->update();
        } catch (\Exception $e) {
            throw new DatabaseException('Update exception.');
        }
        
        // Return result
        if ((isset($options['format']) && $options['format'] == 'id')
            || (!isset($options['format']) && $this->outputFormat == 'id')) {
            return $this->idOutput($id, $record);                       // Return ID
        }
        return $this->modelOutput($model, $options);                    // Return object
    }
    
    /**
     * Find a data entry, given its id.
     */
    public function find($id, $options = array())
    {
        $this->clearBuilder();
        $model = $this->builder;

        // With relations
        $this->withRelations = isset($options['with']) ? $options['with'] : [];
        $model = $this->withRelations($model);

        $model = $model->find($id);
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
        } catch (\Exception $e) {
            throw new NotFoundException('Record to be deleted does not exist.');
        }

        // Delete it
        if (!$model->delete()) 
            throw new DatabaseException('Deleting error.');       
        
        //Cache::decrement('table.'.$this->table.'.count');
        return true;
    }
    
    
    // =========================================== PROTECTED ==================================== //
    
 
    /**
     * Clear the builder.
     */
    protected function clearBuilder()
    {
        $this->builder = $this->newModel();
    }
   
    /**
     * Create a new model.
     */
    protected function newModel()
    {
        $model = new $this->model;
        $model->setConnection($this->connection);
        return $model;
    }

    /**
     * Prepare a data before recording it: post-processing.
     */
    protected function recordInput($record, $model = null)
    {
        // Transform the associative array into object
        $record['data'] = json_decode(json_encode($record['data']));
        return $record;
    }

    /**
     * Implement Eloquent 'with'.
     */
    protected function withRelations($builder)
    {
        $keys = array_keys($this->relations);
        $relations = array_intersect($this->withRelations, $keys);
        return $builder->with($relations);
    } 


}
