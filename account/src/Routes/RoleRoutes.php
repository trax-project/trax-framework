<?php

namespace Trax\Account\Routes;

use Trax\DataStore\Routes\DataRoutes;

class RoleRoutes extends DataRoutes
{
    /**
     * The data model.
     */
    protected $model = 'Role';

    /**
     * Only these API methods. May be overridden.
     */
    protected $only = array('store', 'get', 'find', 'update', 'delete');


}
