<?php

namespace Trax\UI\ViewComposers;

use Illuminate\Support\Facades\View;

trait ViewComposer
{

    /**
     * Check if we are at the view root.
     */
    protected function viewRoot($view)
    {
        $viewName = $view->name();
        if (strpos($viewName, 'parts') !== false) return false;
        if (strpos($viewName, 'layout') !== false) return false;
        return true;
    }

}