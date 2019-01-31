<?php

namespace Trax\Account\Routes;

use Trax\DataStore\Routes\DataRoutes;

class EntityRoutes extends DataRoutes
{
    /**
     * The data model.
     */
    protected $model = 'Entity';

    /**
     * Route name.
     */
    protected $routeName = 'account.entities';

    /**
     * Only these API methods. May be overridden.
     */
    protected $only = array('store', 'get', 'find', 'update', 'delete');


}
