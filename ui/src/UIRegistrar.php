<?php

namespace Trax\UI; 

class UIRegistrar
{
    /**
     * List of language files to load for JS access.
     */
    protected $langFiles = [];

    /**
     * Main menu.
     */
    protected $sideMenu = [];

    /**
     * User menu.
     */
    protected $userMenu = [];

    /**
     * Top menu.
     */
    protected $topMenu = [];


    /**
     * Register plugin UI.
     */
    public function register($ui)
    {
        $this->init();
        $ui->registerLangFiles($this->langFiles);
        $ui->registerSideMenu($this->sideMenu);
        $ui->registerUserMenu($this->userMenu);
        $ui->registerTopMenu($this->topMenu);
    }

    /**
     * Init hook.
     */
    public function init()
    {
    }

}
