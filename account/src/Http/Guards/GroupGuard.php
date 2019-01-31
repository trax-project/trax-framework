<?php

namespace Trax\Account\Http\Guards;

use Illuminate\Http\Request;

use Trax\Foundation\Http\Guards\StatusGuard;
use Trax\Account\Exceptions\ForbiddenException;

trait GroupGuard
{
    use StatusGuard;

    /**
     * Accept only draft status when storing.
     */
    protected $storeDraftOnly = true;


     /**
     * Guard update request.
     */
    protected function statusGuardUpdateRequest(Request $request, $model)
    {
        // Archived groups can't be modified
        if ($model->data->status_code == 'archived') {
            throw new ForbiddenException(__('trax-account::common.cant_update_archived_group'));
        } 

        // Groups with archived registrations can't be modified, except their status
        $registrationStatus = $model->registrations()->pluck('data')->pluck('status_code')->toArray();
        if (in_array('archived', $registrationStatus)) {

            if (($request->has('name') && $model->data->name != $request->input('name'))
                || ($request->has('description') && $model->data->description != $request->input('description'))) {
                    throw new ForbiddenException(__('trax-account::common.cant_update_group_with_archived_registration'));
            }
        }
    }

    /**
     * Guard register request.
     */
    protected function guardRegisterRequest(Request $request, $leftModel, $rightModel)
    {
        // Relation guard
        $this->guardUserRelation($request, $leftModel, $rightModel);
    }

    /**
     * Guard unregister request.
     */
    protected function guardUnregisterRequest(Request $request, $leftModel, $rightModel)
    {
        // Relation guard
        $this->guardUserRelation($request, $leftModel, $rightModel);

        // Unregister guard
        if ($leftModel->data->status_code == 'maintenance') {
            $registrationIds = $leftModel->registrations()->pluck('id')->toArray();
            $statesNb = $rightModel->learningUnitStates()->whereIn('registration_id', $registrationIds)->count();
            if ($statesNb) throw new ForbiddenException(__('trax-account::common.cant_remove_user_from_group'));
        }
    }

    /**
     * Guard toggle request.
     */
    protected function guardUserRelation(Request $request, $leftModel, $rightModel)
    {
        // Active and archived groups can't be modified
        if (in_array($leftModel->data->status_code, ['active', 'archived'])) {
            throw new ForbiddenException(__('trax-account::common.cant_update_active_and_archived_group'));
        } 

        // Groups with archived registrations can't be modified, except their status
        $registrationStatus = $leftModel->registrations()->pluck('data')->pluck('status_code')->toArray();
        if (in_array('archived', $registrationStatus)) {

            throw new ForbiddenException(__('trax-account::common.cant_update_group_with_archived_registration'));
        }
    }

}
