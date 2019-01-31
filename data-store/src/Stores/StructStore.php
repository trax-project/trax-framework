<?php

namespace Trax\DataStore\Stores;

use Trax\DataStore\Stores\SelectableStore;
use Trax\DataStore\Stores\DataStoreFilter;

trait StructStore
{
    use SelectableStore, DataStoreFilter;

    /**
     * Props used for the global search.
     */
    protected $traitGlobalSearchScopes = array('data.name');


    /**
     * Init store.
     */
    protected function initStore()
    {
        $this->globalSearchScopes = $this->traitGlobalSearchScopes;
        $this->defaultOrderingCol = $this->sortable ? 'data.order' : 'data.name';
    }

    /**
     * Prepare a data before recording it.
     */
    protected function dataInputPre($record, $options, $model = null)
    {
        $ignoreSort = isset($options['ignoreSort']) && $options['ignoreSort'];

        // Prepare search criteria to filter items
        $search = $this->searchBrothersCriteria($record);

        // Generate default data at the creation
        if (is_null($model)) {

            // Be sure to provide an UUID
            if (!isset($record['uuid']) || empty($record['uuid']))
                $record['uuid'] = traxUuid();

            // Add order
            if ($this->sortable) {
                $last = $this->brothers($record, $search)->last();
                $order = $last ? $last->data->order + 1 : 0;
                $record['order'] = $this->normalizedOrder($order);
            }

        } else {

            // Check order change
            if ($this->sortable && !$ignoreSort && isset($record['order'])) {
                $parentChanged = isset($record['parent_id']) && $model->parent_id != $record['parent_id'];
                if (!isset($model->data->order) || $model->data->order != $record['order'] || $parentChanged) {

                    // Shift brothers
                    $brothers = $this->brothers($record, $search, ['flat' => true]);
                    $i = 0;
                    foreach($brothers as $brother) {
                        if ($i == $record['order']) $i++;
                        $brother->order = $this->normalizedOrder($i);
                        $this->update($brother->id, $brother, ['ignoreSort' => true]);
                        $i++;
                    }
                    
                    // Preserve original data, change only the order
                    $original = $this->find($model->id, ['flat' => true]);
                    $original->order = $this->normalizedOrder($record['order']);
                    if ($parentChanged) $original->parent_id = $record['parent_id'];
                    $record = $this->normalizedJsonData($original);
                }
            }
        }

        return parent::dataInputPre($record, $options, $model);
    }

    /**
     * Get data entries.
     */
    public function get($args = array(), $options = array())
    {
        if (isset($options['flatStruct']) && $options['flatStruct']) return $this->filterGet($args, $options);
        return $this->structGet($args, $options);
    }

    /**
     * Get data entries. May be called directly by the trait users.
     */
    protected function structGet($args = array(), $options = array())
    {
        // Get items
        $search = isset($args['search']) ? $args['search'] : [];
        $filters = isset($args['filters']) ? $args['filters'] : [];
        $parentId = isset($search['parent_id']) ? $search['parent_id'] : null;
        $items = $this->children($parentId, $search, $filters, $options);

        // Populate children
        foreach ($items as &$item) {
            $item->children = $this->children($item->id, $search, $filters, $options);
        }

        return $items;
    }

    /**
     * Format order to avoid ordering issue.
     */
    protected function normalizedOrder($order)
    {
        return $order < 1000 ? $order + 1000 : $order;
    }

    /**
     * Get search criteria to filter brothers.
     */
    protected function searchBrothersCriteria($record)
    {
        return $this->standardSearchBrothersCriteria($record);
    }

    /**
     * Get search criteria to filter brothers.
     */
    protected function standardSearchBrothersCriteria($record)
    {
        $search = [];

        // Split by index
        if (isset($record['index_id']) && !empty($record['index_id'])) {
            $search['index_id'] = $record['index_id'];
        }

        // Split by type
        if ($this->slitByType && isset($record['type_code']) && !empty($record['type_code'])) {
            $search['type_code'] = $record['type_code'];
        }
        return $search;
    }


    /**
     * Get children items.
     */
    protected function children($id = null, $search = [], $filters = [], $options = [])
    {
        if (is_null($id)) {
            $search = array_merge($search, [(object)array('key' => 'parent_id', 'operator' => 'NULL')]);
        } else {
            $search = array_merge($search, ['parent_id' => $id]);
        }
        $res = $this->filterGet(['search' => $search, 'filters' => $filters], $options);
        return isset($res->data) ? $res->data : $res;
    }

    /**
     * Get brothers items.
     */
    protected function brothers($record, $search = [], $options = [])
    {
        if (isset($record['parent_id']) && !empty($record['parent_id'])) {
            $search = array_merge($search, ['parent_id' => $record['parent_id']]);
        } else {
            $search = array_merge($search, [(object)array('key' => 'parent_id', 'operator' => 'NULL')]);
        }
        if (isset($record['id'])) {
            $search = array_merge($search, [(object)array('key' => 'id', 'operator' => '!=', 'value' => $record['id'])]);
        }
        $res = parent::get(['search' => $search], $options);
        return isset($res->data) ? $res->data : $res;
    }

    /**
     * Get select data.
     */
    protected function prepareSelect($items, $idProp)
    {
        $select = [];
        foreach ($items as $item) {
            if (!isset($item->children) || $item->children->isEmpty()) continue;
            $children = [];
            foreach ($item->children as $child) {
                $children[] = ['name' => $child->data->name, 'value' => $child->$idProp];
            }
            $select[$item->data->name] = $children;
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
            if (!isset($item->children) || $item->children->isEmpty()) continue;
            foreach ($item->children as $child) {
                $names[$child->$idProp] = $child->data->name;
            }
        }
        return $names;
    }


}
