<?php

namespace Trax\Account\Models;

traxCreateModelSwitchClass('Trax\Account\Models', 'trax-account', 'Entity');

use Trax\DataStore\Models\Struct;

class Entity extends EntityModel
{
    use Struct;

    /**
     * The table associated with the model.
     */
    protected $table = 'trax_account_entities';


    /**
     * Children.
     */
    public function children()
    {
        return $this->hasMany('Trax\Account\Models\Entity', 'parent_id');
    }

    /**
     * Cousins: all entities with the same type.
     */
    public function cousins($onlyChildren = false)
    {
        $res = $this->where('type_code', $this->type_code);
        if ($onlyChildren) $res = $res->whereNotNull('parent_id');
        return $res;
    }

}
