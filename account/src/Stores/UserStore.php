<?php

namespace Trax\Account\Stores;

use Trax\DataStore\Stores\DataStoreFilter;

traxCreateDataStoreSwitchClass('Trax\Account\Stores', 'trax-account', 'User');

class UserStore extends UserStoreSwitch
{
    use DataStoreFilter;

    /**
     * The attributes that should be visible.
     */
    protected $visible = ['id', 'uuid', 'username', 'email', 'admin', 'active', 'source_code', 'role_id', 'entity_type_code', 'organization_id', 'entity_id', 'data', 'created_at', 'updated_at'];

    /**
     * The attributes that should not be changed.
     */
    protected $protected = ['id', 'uuid', 'created_at', 'updated_at'];
    
    /**
     * Columns.
     */
    protected $columns = array('uuid', 'username', 'email', 'password', 'admin', 'active', 'source_code', 'role_id', 'entity_type_code', 'organization_id', 'entity_id');

    /**
     * Props used for the global search.
     */
    protected $globalSearchScopes = array('username', 'email', 'data.firstname', 'data.lastname');

    /**
     * Virtual columns.
     */
    protected $virtualColumns = ['data.firstname' => 'firstname', 'data.lastname' => 'lastname'];

    /**
     * Relations.
     */
    protected $relations = [
        'role' => ['type' => 'single', 'table' => 'trax_account_roles'], 
        'organization' => ['type' => 'single', 'table' => 'trax_account_entities'],
        'entity' => ['type' => 'single', 'table' => 'trax_account_entities'],
        'groups' => ['type' => 'multiple', 'table' => 'trax_account_groups']
    ];


    /**
     * Default values.
     */
    protected $defaultValues = [
        'uuid' => ['type' => 'function' , 'default' => 'traxUuid'],
        'username' => ['type' => 'prop', 'default' => 'email'],
        'lang' => ['type' => 'config', 'default' => 'app.locale'],
        'entity_type_code' => ['type' => 'config', 'default' => 'trax-account.options.entity-types.default'],
        'rights_level_code' => ['type' => 'value', 'default' => 'global'],
        'password' => ['type' => 'function', 'default' => 'traxUuid'],
    ];

    /**
     * Filters.
     */
    protected $filters = [
        'ids' => ['type' => 'In', 'target' => 'id'],
    ];


    /**
     * Prepare a data before recording it.
     */
    protected function dataInputPre($record, $options, $model = null)
    {            
        // Remove password confirmation
        if (isset($record['password_confirmation'])) unset($record['password_confirmation']);
        if (isset($record['current_password'])) unset($record['current_password']);

        // Remove empty password
        if (isset($record['password']) && empty($record['password'])) unset($record['password']);

        // Encrypt password
        if (isset($record['password'])) $record['password'] = app('hash')->make($record['password']);

        // Preserve specific props
        $this->keepJsonProp('status', $record, $model);
        $this->keepJsonProp('preferences', $record, $model);
        $this->keepJsonProp('picture', $record, $model);

        return parent::dataInputPre($record, $options, $model);
    }

    /**
     * Preserve a JSON property.
     */
    protected function keepJsonProp($prop, &$record, $model = null)
    {            
        if (isset($model) && isset($model->data->$prop)) $record[$prop] = $model->data->$prop;
    }

}
