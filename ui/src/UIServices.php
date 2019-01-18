<?php

namespace Trax\UI;

use Trax\UI\Options\Languages;
use Trax\UI\Options\Backgrounds;
use Trax\UI\ViewComposers\ViewComposer;
use Trax\UI\ViewComposers\LangViewComposer;
use Trax\UI\ViewComposers\NavViewComposer;
use Trax\UI\ViewComposers\UserViewComposer;

class UIServices
{
    use ViewComposer, LangViewComposer, NavViewComposer, UserViewComposer;

    /**
     * The application instance.
     */
    protected $app;

    /**
     * List of language files to load for JS access.
     */
    protected $langFiles = [];

    /**
     * Main menu.
     */
    protected $sideMenu = [
        'users' => [
            'icon' => 'people',
            'title' => 'trax-ui::common.users',
            'children' => [],
        ],
        'training' => [
            'icon' => 'book',
            'title' => 'trax-ui::common.training',
            'children' => [],
        ],
        'data' => [
            'icon' => 'dns',
            'title' => 'trax-ui::common.data',
            'children' => [],
        ],
        'test' => [
            'icon' => 'bug_report',
            'title' => 'trax-ui::common.test',
            'children' => [],
        ],
        'settings' => [
            'icon' => 'settings',
            'title' => 'trax-ui::common.settings',
            'children' => [],
        ],
    ];

    /**
     * User menu.
     */
    protected $userMenu = [];

    /**
     * Top menu.
     */
    protected $topMenu = [];

    /**
     * Languages.
     */
    protected $languages;

    /**
     * Backgrounds.
     */
    protected $backgrounds;


    /**
     * Create a new data manager instance.
     */
    public function __construct($app)
    {
        $this->app = $app;

        $languagesClass = config('trax.options.languages.model');
        $this->languages = new $languagesClass();

        $backgroundsClass = config('trax.options.backgrounds.model');
        $this->backgrounds = new $backgroundsClass();
    }

    /**
     * Return languages.
     */
    public function languages()
    {
        return $this->languages;
    }

    /**
     * Return backgrounds.
     */
    public function backgrounds()
    {
        return $this->backgrounds;
    }

    /**
     * Register lang files for JS UI.
     */
    public function registerLangFiles($langFiles, $compose = false)
    {
        $this->langFiles = array_merge($this->langFiles, $langFiles);
        if ($compose) $this->composeLang();
    }

    /**
     * Register nav data.
     */
    public function registerNav()
    {
        $this->composeNav();
    }

    /**
     * Register user data.
     */
    public function registerUser($accountServices)
    {
        $this->composeUser($accountServices);
    }

    /**
     * Register main menu.
     */
    public function registerSideMenu($menuEntries)
    {
        foreach($menuEntries as $location => $items) {
            if (!isset($this->sideMenu[$location])) {
                continue;
            } else {
                $this->sideMenu[$location]['children'] = array_merge($this->sideMenu[$location]['children'], $items);
            }
        }
    }

    /**
     * Register user menu.
     */
    public function registerUserMenu($menuEntries)
    {
        $this->userMenu = array_merge($this->userMenu, $menuEntries);
    }

    /**
     * Register top menu.
     */
    public function registerTopMenu($menuEntries)
    {
        $this->topMenu = array_merge($this->topMenu, $menuEntries);
    }

    /**
     * Check nav permissions.
     */
    protected function allowedEntries($menuEntries)
    {
        $res = [];
        foreach($menuEntries as $key => $item) {
            if (isset($item['children'])) {
                $item['children'] = $this->allowedEntries($item['children']);
            }
            if (isset($item['permission'])) {
                $allowed = \TraxAccount::authorizer()->allowed($item['permission']);
                if (!$allowed) continue;
            }
            $res[$key] = $item;
        }
        return $res;
    }


}
