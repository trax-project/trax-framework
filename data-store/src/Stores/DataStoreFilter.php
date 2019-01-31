<?php

namespace Trax\DataStore\Stores;

trait DataStoreFilter 
{

    /**
     * Get data entries.
     */
    public function get($args = array(), $options = array())
    {
        return $this->filterGet($args, $options);
    }

    /**
     * Count the data entries.
     */
    public function count($args = [])
    {
        return $this->filterCount($args);
    }

    /**
     * Get data entries.
     */
    protected function filterGet($args = array(), $options = array())
    {
        $args = $this->filterArgs($args);
        return parent::get($args, $options);
    }

    /**
     * Count data entries.
     */
    protected function filterCount($args = array())
    {
        $args = $this->filterArgs($args);
        return parent::count($args);
    }

    /**
     * Prepare args.
     */
    protected function filterArgs($args = array())
    {
        if (isset($this->filters) && isset($args['filters'])) {

            // Create search arg
            if (!isset($args['search'])) $args['search'] = [];

            // Filters
            foreach ($this->filters as $key => $def) {
                $filterName = 'add' . $def['type'] . 'FilterCondition';
                $filterOptions = isset($def['options']) ? $def['options'] : [];
                $filterOperator = isset($def['operator']) ? $def['operator'] : null;
                $this->$filterName($args, $key, $def['target'], $filterOptions, $filterOperator);
            }

            // Free filters arg
            unset($args['filters']);
        }
        return $args;
    }
    

    // =================================== FILTERS ====================================//


    /**
     * Add EXISTS condition to search arg
     */
    protected function addExistsFilterCondition(&$args, $id, $target, $options = [], $operator = null)
    {
        if (($filter = $this->getFilterValue($args, $id)) === false) return false;
        $args['search'][] = (object)array('key' =>$target, 'operator' => 'EXISTS');
    }

    /**
     * Add NOT_EXISTS condition to search arg
     */
    protected function addNotExistsFilterCondition(&$args, $id, $target, $options = [], $operator = null)
    {
        if (($filter = $this->getFilterValue($args, $id)) === false) return false;
        $args['search'][] = (object)array('key' =>$target, 'operator' => 'NOT_EXISTS');
    }

    /**
     * Add BOOL condition to search arg
     */
    protected function addBoolFilterCondition(&$args, $id, $target, $options = [], $operator = null)
    {
        if (($filter = $this->getFilterValue($args, $id)) === false) return false;
        if (isset($options['value'])) $filter = $options['value'];
        $filter = boolval($filter) && $filter != "false";
        $args['search'][] = (object)array('key' => $target, 'operator' => 'BOOL', 'value' => $filter);
        return $filter;
    }

    /**
     * Add COMP condition to search arg
     */
    protected function addNumFilterCondition(&$args, $id, $target, $options = [], $operator = null)
    {
        if (($filter = $this->getFilterValue($args, $id)) === false) return false;
        if (isset($options['value'])) $filter = $options['value'];
        $filter = $filter+0;
        if (isset($options['orNull']) && $options['orNull']) {
            $args['search'][] = [
                (object)array('key' => $target, 'operator' => $operator, 'value' => $filter),
                (object)array('key' => $target, 'operator' => 'NOT_EXISTS'),
            ];
        } else {
            $args['search'][] = (object)array('key' => $target, 'operator' => $operator, 'value' => $filter);
        }
        return $filter;
    }

    /**
     * Add EQUAL condition to search arg
     */
    protected function addEqualFilterCondition(&$args, $id, $targets, $options = [], $operator = null)
    {
        if (($filter = $this->getFilterValue($args, $id)) === false) return false;
        if (isset($options['prefix'])) $filter = $options['prefix'].$filter;
        if (isset($options['value'])) $filter = $options['value'];
        if (!is_array($targets)) $targets = [$targets];
        $orConditions = [];
        foreach($targets as $target) {
            if (isset($options['default']) && $options['default'] == $filter) {
                $orConditions[] = (object)array('key' =>$target, 'operator' => 'EQUAL_OR_NULL', 'value' => $filter);
            } else {
                $orConditions[$target] = $filter;
            }
        }
        $args['search'][] = $orConditions;
        return $filter;
    }

    /**
     * Add IN condition to search arg
     */
    protected function addInFilterCondition(&$args, $id, $target, $options = [], $operator = null)
    {
        if (($filter = $this->getFilterValue($args, $id)) === false) return false;
        $args['search'][] = (object)array('key' => $target, 'operator' => 'IN', 'value' => $filter);
        return $filter;
    }

    /**
     * Add LIKE condition to search arg
     */
    protected function addLikeFilterCondition(&$args, $id, $target, $options = [], $operator = null)
    {
        if (($filter = $this->getFilterValue($args, $id)) === false) return false;
        $args['search'][] = (object)array('key' => $target, 'operator' => 'LIKE', 'value' => '%' . $filter . '%');
        return $filter;
    }

    /**
     * Add SINCE condition to search arg (exclusive)
     */
    protected function addSinceFilterCondition(&$args, $id, $target, $options = [], $operator = null)
    {
        if (($filter = $this->getFilterValue($args, $id)) === false) return false;
        $date = traxIsoTimestamp($filter);
        $args['search'][] = (object)array('key' =>$target, 'operator' => '>=', 'value' => $date);
        return $date;
    } 

    /**
     * Add UNTIL condition to search arg (inclusive)
     */
    protected function addUntilFilterCondition(&$args, $id, $target, $options = [], $operator = null)
    {
        if (($filter = $this->getFilterValue($args, $id)) === false) return false;
        $date = traxIsoTimestamp($filter);
        $args['search'][] = (object)array('key' =>$target, 'operator' => '<', 'value' => $date);
        return $date;
    } 


    // =================================== UTILITIES ====================================//


    /**
     * Get filter value
     */
    protected function getFilterValue($args, $id)
    {
        $parts = explode('.', $id);
        $filter = $args['filters'];
        foreach ($parts as $part) {
            if (!isset($filter[$part]) || $filter[$part] == '') return false;
            $filter = $filter[$part];
        }
        return $filter;
    }

    /**
     * Get booleanfilter value
     */
    protected function getBoolFilterValue($args, $id)
    {
        if (($bool = $this->getFilterValue($args, $id)) === false) return false;
        return $bool && ($bool != "false");
    }


}
