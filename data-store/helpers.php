<?php

/**
 * Get the options class from its model name (Facade.Class).
 */
if (!function_exists('traxOptions')) {

    function traxOptions($name)
    {
        list($facadeName, $optionClassName) = explode('.', $name);
        $facadeName = '\\' . $facadeName;
        $functionName = lcfirst($optionClassName);
        return $facadeName::$functionName();
    }
}

/**
 * Get the DB connection given a model name.
 */
if (!function_exists('traxConnection')) {

    function traxConnection($plugin, $modelName)
    {
        $connection = config($plugin . '.stores.' . $modelName . '.connection');
        if (empty($connection)) {
            $driver = config($plugin . '.stores.' . $modelName . '.driver');
            if ($driver == 'mongo') $connection = 'mongodb';
            else $connection = config('database.default');
        }
        return $connection;
    }
}

/**
 * Get the DB connection given a model name.
 */
if (! function_exists('traxUnicityId')) {
    
    function traxUnicityId($plugin, $modelName) {
        $driver = config($plugin . '.stores.'.$modelName.'.driver');
        if ($driver == 'mongo') return '_id';
        return 'id';
    }
}

/**
 * Create a parent class for a store.
 */
if (! function_exists('traxCreateDataStoreSwitchClass')) {
    
    function traxCreateDataStoreSwitchClass($namespace, $plugin, $className) {
        $code = "namespace ".$namespace."; ";
        $driver = config($plugin . '.stores.'.$className.'.driver');
        if ($driver == 'mongo') {
            $code .= "class ".$className."StoreSwitch extends \Trax\DataStore\Stores\DataStoreMongo {}";
        } else {
            if ($driver == 'eloquent') {
                $code .= "class ".$className."StoreSwitch extends \Trax\DataStore\Stores\DataStoreEloquent { ";
            } else {
                $code .= "class ".$className."StoreSwitch extends \Trax\DataStore\Stores\DataStoreDatabase { ";
            }
            if (config('trax.db.mariadb') && config('database.default') == 'mysql') {
                $code .= "use \Trax\DataStore\Stores\DataStoreMariaDb; ";
            } else if (config('database.default') == 'pgsql') {
                $code .= "use \Trax\DataStore\Stores\DataStorePostgreSql; ";
            }
            $code .= " }";
        }
        eval($code);
    }
}

/**
 * Create a parent class for a controller.
 */
if (! function_exists('traxCreateStoreControllerSwitchClass')) {
    
    function traxCreateStoreControllerSwitchClass($namespace, $className) {
        $code = "namespace ".$namespace."; ";
        if (!traxRunningInLumen() && strpos(\Request::url(), 'ajax') !== false) {
            $code .= "class ".$className."ControllerSwitch extends \Trax\DataStore\Http\Controllers\DataAjaxController {}";
        } else {
            $code .= "class ".$className."ControllerSwitch extends \Trax\DataStore\Http\Controllers\DataWsController {}";
        }
        eval($code);
    }
}

/**
 * Create a parent class for an ELoquent or MongoDB model.
 */
if (! function_exists('traxCreateModelSwitchClass')) {
    
    function traxCreateModelSwitchClass($namespace, $plugin, $className) {
        $code = "namespace ".$namespace."; ";
        $driver = config($plugin . '.stores.'.$className.'.driver');
        if ($driver == 'mongo') {
            $code .= "class " . $className . "Model extends \Jenssegers\Mongodb\Eloquent\Model {
                use \Trax\DataStore\Models\TraxModel;
            }";
        } else {
            $code .= "class " . $className . "Model extends \Illuminate\Database\Eloquent\Model {
                use \Trax\DataStore\Models\TraxModel;
            }";
        }
        eval($code);
    }
}

/**
 * Create a parent class for an ELoquent or MongoDB authenticatable class.
 */
if (! function_exists('traxCreateAuthenticatableSwitchClass')) {
    
    function traxCreateAuthenticatableSwitchClass($namespace, $plugin, $className) {
        $code = "namespace ".$namespace."; ";
        $driver = config($plugin.'.stores.'.$className.'.driver');
        if ($driver == 'mongo') {
            $code .= "class ".$className."Authenticatable extends \Jenssegers\Mongodb\Auth\User {}";
        } else if (config('trax-account.files.enabled')) {
            $code .= "class " . $className . "Authenticatable extends \Trax\Account\Models\UserWithFiles {}";
        } else {
            $code .= "class ".$className."Authenticatable extends \Illuminate\Foundation\Auth\User {}";
        }
        eval($code);
    }
}


