<?php

namespace Trax\Account\Routes;

use Trax\DataStore\Routes\DataRoutes;

class AgreementRoutes extends DataRoutes
{
    /**
     * The data model.
     */
    protected $model = 'Agreement';

    /**
     * Only these API methods. May be overridden.
     */
    protected $only = array('store', 'update');

}
