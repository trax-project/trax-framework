<?php

namespace Trax\UI\ViewComposers;

use Illuminate\Support\Facades\View;

trait LangViewComposer
{

    /**
     * Create and print a JSON var with all language entries.
     */
    protected function composeLang()
    {
        View::composer('*', function ($view) {
            if (!$this->viewRoot($view)) return;
            $lang = array();
            foreach ($this->langFiles as $file) {
                list($plugin, $theme) = explode('::', $file);
                $plugin = str_replace('-', '_', $plugin);
                if (!isset($lang[$plugin])) $lang[$plugin] = array();
                $lang[$plugin][$theme] = trans($file);
            }
            $view->with('lang', $lang);
        });
    }

}