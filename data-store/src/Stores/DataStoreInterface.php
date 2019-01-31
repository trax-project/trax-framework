<?php

namespace Trax\DataStore\Stores;

interface DataStoreInterface
{

    /**
     * Create a new data API instance.
     */
    public function __construct($app, $connection, $table, $model);

    /**
     * Get the name of the model.
     */
    public function model();

    /**
     * Get data entries.
     *
     * $args can be passed to filter data, with the following optional properties:
     * -  global-search: something to search globally
     * -  search: an associative array of data field => value to perform a the search (ALL)
     * -  since & until: to filter the returned data entries by creation dates
     * -  order-by & order-dir: to order the returned data entries 
     * -  limit & offset: to paginate the returned data entries
     * 
     * By default, return a Collection of data entries.
     *
     * $options can be used to set the returned format, with the following options:
     * -  count: can be set to true to return an object with 3 properties :
     *      > data: a Collection of data entries
     *      > total_count: total number of objects
     *      > filtered_count: number of filtered objects
     * - filteredCount: similar to count except the total_count is not included (performance)
     * - totalCount: similar to count except the filtered_count is not included (performance)
     * - flat: when set to true, models are returned as flat objects, without the 'data' prop,
     *      with an id, with props, but without dates.
     * - with: list of relations and options to instantiate.
     */
    public function get($args = array(), $options = array());

    /**
     * Store a data.
     * $data must be an associative array or a valid JSON string.
     * 
     * By default, return the ID of the stored data entry (integer or string).
     * Return false when something goes wrong.
     * 
     * $options can be used to set the returned format, with the following options:
     * -  format: can be set to 'id' or 'object'.
     * -  flat: format is 'object' and when set to true, models are returned as flat objects, 
     *          without the 'data' prop, with an id, with props, but without dates.
     */
    public function store($data, $options = array());

    /**
     * Update a data.
     * $data must be an associative array or a valid JSON string.
     * 
     * By default, return the ID of the updated data entry (integer or string).
     * Return false when something goes wrong.
     * 
     * $options can be used to set the returned format, with the following options:
     * -  format: can be set to 'id' or 'object'.
     * -  flat: format is 'object' and when set to true, models are returned as flat objects, 
     *          without the 'data' prop, with an id, with props, but without dates.
     */
    public function update($id, $data, $options = array());

    /**
     * Find a data entry, given its id.
     * 
     * Return the data entry.
     * Throw a NotFoundException when it doesn't exist.
     * 
     * $options can be used to set the returned format, with the following optional properties:
     * -  flat: format is 'object' and when set to true, models are returned as flat objects, 
     *          without the 'data' prop, with an id, with props, but without dates.
     * -  with: list of relations and options to instantiate.
     */
    public function find($id, $options = array());

    /**
     * Find a data entry, given a unique field.
     * 
     * Return the data entry.
     * Throw a NotFoundException when it doesn't exist.
     * 
     * $options can be used to set the returned format, with the following optional properties:
     * -  flat: format is 'object' and when set to true, models are returned as flat objects, 
     *          without the 'data' prop, with an id, with props, but without dates.
     * -  with: list of relations and options to instantiate.
     */
    public function findBy($field, $value, $options = array());
    
    /**
     * Delete a data entry, given its id.
     * 
     * Return false if something goes wrong, or true if everything is OK.
     */
    public function delete($id);

    /**
     * Delete all the data entries.
     */
    public function clear();
    
    /**
     * Count all the data entries.
     */
    public function count();
    
}
