<?php

namespace Trax\Account\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use Trax\Account\AccountServices;
use Trax\Account\Http\Validations\UserValidation;
use Trax\Account\Http\Guards\UserGuard;

traxCreateStoreControllerSwitchClass('Trax\Account\Http\Controllers', 'User');

class UserController extends UserControllerSwitch
{
    use UserValidation, UserGuard;

    /**
     * Delete exception message key.
     */
    protected $deleteExceptionMessageKey = 'trax-account::common.user_deletion_exception';

    
    /**
     * Create a new controller instance.
     */
    public function __construct(AccountServices $services)
    {
        $this->services = $services;
        $this->store = $this->services->users();
    }

    /**
     * Post user picture.
     */
    public function postPicture(Request $request, $id)
    {
        // Permissions
        $this->allowsUpdate($request, $id);

        // File check

        // File exists
        if (!$request->hasFile('picture')) abort(400, __('trax-foundation::common.file_uploaded_not_found'));
        $file = $request->file('picture');

        // File is valid
        if (!$file->isValid()) abort(400, __('trax-foundation::common.file_not_valid'));

        // Extension is valid
        $extension = $file->extension();
        if ($extension != 'jpeg') abort(400, __('trax-foundation::common.file_extension_not_valid'));

        // File size is valid
        if ($file->getClientSize() > $file->getMaxFilesize()) abort(400, __('trax-foundation::common.file_max_size_error'));

        // Get user
        try {
            $user = $this->store->find($id);
        } catch (\Exception $e) {
            abort(400, __('trax-foundation::common.file_model_not_found'));
        }

        // Add new file
        $user->addMedia($file->path())->usingName('picture')->usingFileName($user->uuid)->toMediaCollection('avatars');

        // Retreive it
        $url = $user->getFirstMediaUrl('avatars');
        if (!$url) abort(400, __('trax-foundation::common.file_not_found'));

        // Remove slash at the begining
        if ($url[0] == '/') $url = substr($url, 1);

        // User update
        $user->setPicture($url);

        // Response
        return response()->json(['picture' => $url]);
    }

    /**
     * Self check password.
     */
    public function checkPassword(Request $request)
    {
        // Validation
        $request->validate([
            'password' => 'required|string|min:6|passcheck',
        ]);

        // Response
        return response('', 204);
    }

    /**
     * Set preferences.
     */
    public function setPreferences(Request $request)
    {
        Auth::user()->setPreferences($request->all());
        return response('', 204);
    }

}
