<?php

namespace Trax\Account\Permissions;

trait MineRegistrationPermission
{
    use MinePermission;

    /**
     * Get registration.
     */
    protected function registration($request)
    {
        return (new \Trax\Training\Models\GroupRegistration)->find($this->registrationId($request));
    }

    /**
     * Get registration ID.
     */
    protected function registrationId($request)
    {
        $id = $request->route('registration_id');
        if (!$id) $id = $request->input('registration_id');
        return $id;
    }

    /**
     * Check mine registration context.
     */
    protected function mineRegistration($request, $user)
    {
        // Only in a registration context
        $registrationId = $this->registrationId($request);
        if (!$registrationId) return false;

        // Check read access to the registration.
        return $this->manager->check($request, 'read', $user, 'Trax\Training\Models\GroupRegistration', $registrationId);
    }

    /**
     * Mine registration function with callback.
     */
    protected function mineRegistrationCallback($getIdsFunction, $request, $type, $user, $model, $id = null)
    {
        // Only in a registration context
        if (!$this->mineRegistration($request, $user)) return false;

        return $this->mineCallback($getIdsFunction, $request, $type, $user, $model, $id);
    }

}

