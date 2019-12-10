<?php

namespace Trax\Account;

use Trax\Account\Exceptions\ForbiddenException;
use Trax\Account\Permissions\PermissionModel;

use TraxAccount;

trait DataStoreAuthorizer
{
    /**
     * Data model.
     */
    protected $dataAuthorizerModel;


    /**
     * Specify the model.
     */
    public function authorizerModel($model)
    {
        $this->dataAuthorizerModel = $model;
        return $this;
    }

    /**
     * Check create permission on the store.
     */
    public function allowsCreate($request)
    {
        return $this->allowsDataStore($request, 'create');
    }

    /**
     * Check read permission on the store.
     */
    public function allowsRead($request, $id = null)
    {
        return $this->allowsDataStore($request, 'read', $id);
    }

    /**
     * Check update permission on the store.
     */
    public function allowsUpdate($request, $id = null)
    {
        return $this->allowsDataStore($request, 'update', $id);
    }

    /**
     * Check delete permission on the store.
     */
    public function allowsDelete($request, $id = null)
    {
        return $this->allowsDataStore($request, 'delete', $id);
    }

    /**
     * Check permission on the store.
     */
    protected function allowsDataStore($request, $type, $id = null)
    {
        if (!isset($this->dataAuthorizerModel)) $this->dataAuthorizerModel = $this->store->model();
        $user = TraxAccount::authorizer()->getUser();

        // Always allows in these cases...
        if (!$user) {
            if (config('app.env') == 'testing' || $this->isWsRequest($request)) return true;
            throw new ForbiddenException();
        } 
        if ($user->admin) return isset($id) ? true : [];

        // Otherwise...
        $searchArgs = TraxAccount::permissions()->check($request, $type, $user, $this->dataAuthorizerModel, $id);
        if ($searchArgs === false) throw new ForbiddenException();
        return $searchArgs;
    }

    /**
     * Check if the request is a WS request.
     */
    protected function isWsRequest($request)
    {
        $wsBegin = strtolower(explode('\\', $this->store->model())[0]).'/ws';
        $pathBegin = substr($request->path(), 0, strlen($wsBegin));
        return $wsBegin == $pathBegin;
    }
}
