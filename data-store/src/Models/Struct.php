<?php

namespace Trax\DataStore\Models;

trait Struct
{
    /**
     * The attributes that are mass assignable.
     */
    protected $traitFillable = [
        'uuid', 'type_code', 'parent_id', 'index_id', 'data'
    ];

    /**
     * The attributes that should be visible.
     */
    protected $traitVisible = [
        'id', 'uuid', 'type_code', 'type', 'parent_id', 'index_id', 'data', 'children', 'created_at', 'updated_at'
    ];

    /**
     * Definition, needed by Trax Data Stores.
     */
    protected $trax = [
        'columns' => ['uuid', 'type_code', 'parent_id', 'index_id'],
        'protected' => ['uuid'],
        'ignore' => ['children', 'type'],
    ];


    /**
     * Create a new model instance.
     */
    public function __construct()
    {
        $this->structConstruct();
        parent::__construct();
    }

    /**
     * Create a new model instance.
     */
    protected function structConstruct()
    {
        $this->fillable = array_merge($this->fillable, $this->traitFillable);
        $this->visible = array_merge($this->visible, $this->traitVisible);

        if (isset($this->typeCodeModel)) {
            $this->trax['options'] = ['type' => [
                'key' => 'type_code',
                'model' => $this->typeCodeModel,
            ]];
        }
    }

}
