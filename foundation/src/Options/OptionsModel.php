<?php

namespace Trax\Foundation\Options;

use Trax\Foundation\Exceptions\OptionNotFoundException;

abstract class OptionsModel
{
    /**
     * Where In filter.
     */
    protected $whereIn = [];

    /**
     * Get data.
     */
    abstract protected function data($plural = false);


    /**
     * Where In function.
     */
    public function whereIn($list)
    {
        $this->whereIn = $list;
        return $this;
    }

    /**
     * Find an option by key.
     */
    public function find($key, $plural = false)
    {
        $data = $this->data($plural);
        if (!array_key_exists($key, $data)) throw new OptionNotFoundException();
        return $data[$key];
    }

    /**
     * Get select data.
     */
    public function select($plural = false)
    {
        $res = [];
        foreach ($this->data($plural) as $val => $data) {
            if (!empty($this->whereIn) && !in_array($val, $this->whereIn)) continue;
            $res[] = ['value' => $val, 'name' => $data['name']];
        }
        $this->whereIn = [];
        return $res;
    }

    /**
     * Get names.
     */
    public function names($plural = false)
    {
        return $this->extract('name');
    }

    /**
     * Icons.
     */
    public function icons()
    {
        return $this->extract('icon');
    }

    /**
     * Get tags.
     */
    public function tags()
    {
        return $this->extract('tag');
    }

    /**
     * Get colors.
     */
    public function colors()
    {
        return $this->extract('color');
    }

    /**
     * Edit routes.
     */
    public function editRoutes()
    {
        return $this->extract('edit-route');
    }

    /**
     * Dashboard routes.
     */
    public function dashboardRoutes()
    {
        return $this->extract('dashboard-route');
    }

    /**
     * Extract a property.
     */
    public function extract($propName)
    {
        $res = [];
        foreach ($this->data() as $val => $data) {
            if (!empty($this->whereIn) && !in_array($val, $this->whereIn)) continue;
            if (isset($data[$propName])) $res[$val] = $data[$propName];
        }
        $this->whereIn = [];
        return $res;
    }

    /**
     * Codes.
     */
    public function codes()
    {
        return array_keys($this->data());
    }


}

