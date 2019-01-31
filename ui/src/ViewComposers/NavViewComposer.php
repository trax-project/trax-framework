<?php

namespace Trax\UI\ViewComposers;

use Illuminate\Support\Facades\View;
use Auth;

trait NavViewComposer
{

    /**
     * Pass nav data to views.
     */
    protected function composeNav()
    {
        View::composer('*', function ($view) {
            if (!$this->viewRoot($view)) return;
            if (!Auth::check()) return;
            $view->with('sideMenu', $this->allowedEntries($this->sideMenu));
            $view->with('userMenu', $this->allowedEntries($this->userMenu));
            $view->with('topMenu', $this->allowedEntries($this->topMenu));
        });
    }

}