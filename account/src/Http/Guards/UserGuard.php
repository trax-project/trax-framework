<?php

namespace Trax\Account\Http\Guards;

use Illuminate\Http\Request;
use Auth;

use Trax\Account\Exceptions\ForbiddenException;

trait UserGuard
{

    /**
     * Guard delete request.
     */
    protected function guardDeleteRequest(Request $request, $id = null)
    {
        if ($id == Auth::user()->id) {
            throw new ForbiddenException(__('trax-account::common.user_self_deletion_exception'));
        }
    }

    /**
     * Guard update request.
     */
    protected function guardUpdateRequest(Request $request, $id = null)
    {
        // Guard other account updating
        if ($id != Auth::user()->id) {

            // Can't modifiy password
            if ($request->has('password')) throw new ForbiddenException();

            return;
        }

        // Guard self-updating
        $user = $this->store->find($id);
        if (
            // Values that can't be changed
            ($request->has('source_code') && $user->source_code != $request->input('source_code')) 
            || ($request->has('admin') && $user->admin != $request->input('admin'))
            || ($request->has('active') && $user->active != $request->input('active'))
            || (!$user->admin && (
                
                // Values that can't be changed, except for admin
                ($request->has('organization_id') && !empty($request->input('organization_id')) && is_null($user->organization))
                || ($request->has('organization_id') && !is_null($user->organization) && $request->input('organization_id') != $user->organization_id)

                || ($request->has('entity_id') && !empty($request->input('entity_id')) && is_null($user->entity))
                || ($request->has('entity_id') && !is_null($user->entity) && $request->input('entity_id') != $user->entity_id)

                || ($request->has('role_id') && !empty($request->input('role_id')) && is_null($user->role))
                || ($request->has('role_id') && !is_null($user->role) && $request->input('role_id') != $user->role_id)

                || ($request->has('entity_type_code') && !empty($request->input('entity_type_code')) && is_null($user->entity_type_code))
                || ($request->has('entity_type_code') && !is_null($user->entity_type_code) && $request->input('entity_type_code') != $user->entity_type_code)

                || ($request->has('rights_level_code') && !empty($request->input('rights_level_code')) && (!isset($user->data->rights_level_code) || empty($user->data->rights_level_code)))
                || ($request->has('rights_level_code') && isset($user->data->rights_level_code) && $request->input('rights_level_code') != $user->data->rights_level_code)

                || ($request->has('user_function_code') && !empty($request->input('user_function_code')) && (!isset($user->data->user_function_code) || empty($user->data->user_function_code)))
                || ($request->has('user_function_code') && isset($user->data->user_function_code) && $request->input('user_function_code') != $user->data->user_function_code)
            ))
        ) {
            throw new ForbiddenException();
        }

        // Values that can't be changed depending of authentication settings
        if (config('trax-account.auth.username')) {
            if ($request->has('username') && $user->username != $request->input('username')) {
                throw new ForbiddenException();
            }
        } else {
            if ($request->has('email') && $user->email != $request->input('email')) {
                throw new ForbiddenException();
            }
        } 
    }

}
