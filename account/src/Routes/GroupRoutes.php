<?php

namespace Trax\Account\Routes;

use Trax\DataStore\Routes\DataRoutes;

class GroupRoutes extends DataRoutes
{
    /**
     * The data model.
     */
    protected $model = 'Group';

    /**
     * Only these API methods. May be overridden.
     */
    protected $only = array('store', 'get', 'find', 'update', 'delete', 'members', 'toggle', 'unregister');

    /**
     * Left relations.
     */
    protected $leftRelations = [
        'users' => 'GroupUser'
    ];
    

}
