<?php

namespace Trax\Account; 

use Trax\DataStore\DataStoreServices;
use Trax\Account\Options\RightsLevels;

class AccountServices extends DataStoreServices
{
    /**
     * Namespace.
     */
    protected $namespace = __NAMESPACE__;

    /**
     * Authorizer.
     */
    protected $authorizer;

    /**
     * User sources.
     */
    protected $userSources;

    /**
     * Entity types.
     */
    protected $entityTypes;

    /**
     * User functions.
     */
    protected $userFunctions;

    /**
     * Rights extension.
     */
    protected $rightsLevels;

    /**
     * Permissions.
     */
    protected $permissions;


    /**
     * Init.
     */
    protected function init()
    {
        $this->authorizer = new Authorizer();

        $userSources = config('trax-account.options.user-sources.model');
        $this->userSources = new $userSources();

        $entityTypes = config('trax-account.options.entity-types.model');
        $this->entityTypes = new $entityTypes();

        $userFunctions = config('trax-account.options.user-functions.model');
        $this->userFunctions = new $userFunctions();

        $this->rightsLevels = new RightsLevels();

        $this->permissions = new PermissionsManager();
    }
    
    /**
     * Register permissions.
     */
    public function registerPermissions($permissions, $model = null)
    {
        return $this->permissions->register($permissions, $model);
    }

    /**
     * Get authorizer service.
     */
    public function authorizer()
    {
        return $this->authorizer;
    }

    /**
     * Get user sources.
     */
    public function userSources()
    {
        return $this->userSources;
    }

    /**
     * Get entity types.
     */
    public function entityTypes()
    {
        return $this->entityTypes;
    }

    /**
     * Get user functions.
     */
    public function userFunctions()
    {
        return $this->userFunctions;
    }

    /**
     * Get permissions.
     */
    public function permissions()
    {
        return $this->permissions;
    }

    /**
     * Get rights levels.
     */
    public function rightsLevels()
    {
        return $this->rightsLevels;
    }

    /**
     * Get Entity store.
     */
    public function entities()
    {
        return $this->store('Entity');
    }

    /**
     * Get Role store.
     */
    public function roles()
    {
        return $this->store('Role');
    }

    /**
     * Get User store.
     */
    public function users()
    {
        return $this->store('User');
    }

    /**
     * Get BasicClient store.
     */
    public function basicClients()
    {
        return $this->store('BasicClient');
    }

    /**
     * Get Group store.
     */
    public function groups()
    {
        return $this->store('Group');
    }

    /**
     * Get Agreement store.
     */
    public function agreements()
    {
        return $this->store('Agreement');
    }

}
