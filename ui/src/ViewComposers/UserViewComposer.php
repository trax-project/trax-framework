<?php

namespace Trax\UI\ViewComposers;

use Illuminate\Support\Facades\View;
use Auth;

trait UserViewComposer
{

    /**
     * Pass user data to views.
     */
    protected function composeUser($accountServices)
    {
        View::composer('*', function ($view) use ($accountServices) {
            if (!$this->viewRoot($view)) return;
            if (!Auth::check()) return;
            $userId = Auth::user()->id;
            $user = $accountServices->users()->find($userId);
            $permissions = false;
            if (isset($user->role)) $permissions = $user->role->permissions;
            $user = $user->only(['id', 'uuid', 'email', 'fullname', 'data', 'admin', 'source_code']);
            $user = json_decode(json_encode($user));
            if ($permissions) $user->permissions = $permissions;
            else $user->permissions = (object)[];
            $view->with('user', $user);
        });
    }

}