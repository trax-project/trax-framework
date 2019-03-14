<?php

namespace Trax\Account; 

use Trax\UI\UIRegistrar;

class AccountUI extends UIRegistrar
{
    /**
     * List of language files to load for JS access.
     */
    protected $langFiles = ['trax-account::common', 'trax-account::options'];

    /**
     * Main menu.
     */
    protected $sideMenu = [
        'users' => [
        ],
        'settings' => [
        ],
    ];

    /**
     * User menu.
     */
    protected $userMenu = [
        'my-profile' => [
            'icon' => 'person',
            'title' => 'trax-account::common.my_profile',
            'route' => 'trax.ui.account.my-profile',
        ],
    ];

    /**
     * Init hook.
     */
    public function init()
    {
        // Users
        if (config('trax-account.services.users')) {

            // Side menu
            $this->sideMenu['users']['accounts'] = [
                'title' => 'trax-account::common.accounts',
                'route' => 'trax.ui.account.user.crud',
                'permission' => 'trax_account_user_crud',
            ];
        }

        // Groups
        if (config('trax-account.services.groups')) {

            // Side menu
            $this->sideMenu['users']['groups'] = [
                'title' => 'trax-account::common.groups',
                'route' => 'trax.ui.account.group.crud',
                'permission' => 'trax_account_group_crud',
            ];
        }

        // Roles
        if (config('trax-account.services.roles')) {

            // Side menu
            $this->sideMenu['users']['roles'] = [
                'title' => 'trax-account::common.roles',
                'route' => 'trax.ui.account.role.crud',
                'permission' => 'trax_account_role_crud',
            ];
        }

        // Entities
        if (config('trax-account.services.entities')) {

            // Side menu
            $this->sideMenu['users']['entities'] = [
                'title' => 'trax-account::common.entities',
                'route' => 'trax.ui.account.entity.crud',
                'permission' => 'trax_account_entity_crud',
            ];
        }

        // Agreements
        if (config('trax-account.services.agreements')) {

            // Side menu
            $this->sideMenu['settings']['agreement-edit'] = [
                'title' => 'trax-account::common.user_agreement_title',
                'route' => 'trax.ui.account.agreement.edit',
                'permission' => 'trax_account_agreement_write',
            ];

            // User menu
            $this->userMenu['agreement'] = [
                'icon' => 'subject',
                'title' => 'trax-account::common.user_agreement_title',
                'route' => 'trax.ui.account.agreement.view',
            ];
        }

        // Basic HTTP Clients
        if (config('trax-account.services.basic-clients')) {

            // Side menu
            $this->sideMenu['settings']['basic-clients'] = [
                'title' => 'trax-account::common.basic_clients',
                'route' => 'trax.ui.account.basic-client.crud',
                'permission' => 'trax_account_basic_client_crud',
            ];
        }
    }

}
