<?php

namespace Trax\Foundation\Http\Guards;

use Illuminate\Http\Request;

use Trax\Account\Exceptions\ForbiddenException;

trait StatusGuard
{

    /**
     * Guard store request.
     */
    protected function guardStoreRequest(Request $request)
    {
        // Draft only
        if (isset($this->storeDraftOnly) && $this->storeDraftOnly && $request->input('status_code') != 'draft') {
            throw new ForbiddenException(__('trax-foundation::common.draft_status_creation_error'));
        }

        // Not archived
        if ($request->input('status_code') == 'archived') {
            throw new ForbiddenException(__('trax-foundation::common.archived_status_creation_error'));
        }
    }

    /**
     * Guard update request.
     */
    protected function guardUpdateRequest(Request $request, $id = null)
    {
        $model = $this->store->find($id);
        $newStatus = $request->input('status_code');

       switch($model->data->status_code) {
            case 'draft':
                if (!in_array($newStatus, ['draft', 'active'])) {
                    throw new ForbiddenException(__('trax-foundation::common.draft_status_change_error'));
                }
                break;
            case 'active':
                if (!in_array($newStatus, ['active', 'maintenance', 'archived'])) {
                    throw new ForbiddenException(__('trax-foundation::common.active_status_change_error'));
                }
                break;
            case 'maintenance':
                if (!in_array($newStatus, ['active', 'maintenance', 'archived'])) {
                    throw new ForbiddenException(__('trax-foundation::common.maintenance_status_change_error'));
                }
                break;
            case 'archived':
                if (!in_array($newStatus, ['archived'])) {
                    throw new ForbiddenException(__('trax-foundation::common.archived_status_change_error'));
                }
                break;
        }
        $this->statusGuardUpdateRequest($request, $model);
    }

    /**
     * Guard update request.
     */
    protected function statusGuardUpdateRequest(Request $request, $model)
    {
    }
}
