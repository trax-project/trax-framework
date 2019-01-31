<?php

namespace Trax\DataStore\Stores;

trait SelectableStore
{
    /**
     * Get data entries in a select format.
     */
    public function select($args = array(), $options = array(), $idProp = 'id')
    {
        $items = $this->get($args, $options);
        if (is_array($idProp)) {
            $res = [];
            foreach($idProp as $prop) {
                $res[$prop] = $this->prepareSelect($items, $prop);
            }
            return $res;
        }
        return $this->prepareSelect($items, $idProp);
    }

    /**
     * Get data entries and return their names.
     */
    public function names($args = array(), $options = array(), $idProp = 'id')
    {
        $items = $this->get($args, $options);
        if (is_array($idProp)) {
            $res = [];
            foreach ($idProp as $prop) {
                $res[$prop] = $this->prepareNames($items, $prop);
            }
            return $res;
        }
        return $this->prepareNames($items, $idProp);
    }

    /**
     * Get select data.
     */
    protected function prepareSelect($items, $idProp)
    {
        $select = [];
        foreach ($items as $item) {
            $select[] = ['value' => $item->$idProp, 'name' => $item->data->name];
        }
        return $select;
    }

    /**
     * Get names data.
     */
    protected function prepareNames($items, $idProp)
    {
        $names = [];
        foreach ($items as $item) {
            $names[$item->$idProp] = $item->data->name;
        }
        return $names;
    }

}
