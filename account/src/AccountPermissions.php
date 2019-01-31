<?php

namespace Trax\Account;

class AccountPermissions extends PermissionsRegistrar
{

    /**
     * Get permissions.
     */
    protected function permissions()
    {
        // Default permissions

        $res = [

            // Basic HTTP Clients
            'trax_account_basic_client_default' => [
                'class' => Permissions\BasicClientDefaultPermission::class,
                'model' => 'Trax\Account\Models\BasicClient',
            ],

            // Agreements
            'trax_account_agreement_default' => [
                'class' => Permissions\AgreementDefaultPermission::class,
                'model' => 'Trax\Account\Models\Agreement',
            ],

            // Admin Users
            'trax_account_user_default' => [
                'class' => Permissions\UserDefaultPermission::class,
                'model' => 'Trax\Account\Models\User',
            ],

            // Admin Groups
            'trax_account_group_default' => [
                'class' => Permissions\GroupDefaultPermission::class,
                'model' => 'Trax\Account\Models\Group',
            ],

            // Admin Roles
            'trax_account_role_default' => [
                'class' => Permissions\RoleDefaultPermission::class,
                'model' => 'Trax\Account\Models\Role',
            ],

            // Admin Entities
            'trax_account_entity_default' => [
                'class' => Permissions\EntityDefaultPermission::class,
                'model' => 'Trax\Account\Models\Entity',
            ],
        ];

        // Basic HTTP Clients
        if (config('trax-account.services.basic-clients')) {
            $res['trax_account_basic_client_crud'] = [
                'name' => __('trax-account::common.perm_basic_clients_management'),
                'class' => Permissions\BasicClientCrudPermission::class,
            ];
        }

        // Agreements
        if (config('trax-account.services.agreements')) {
            $res['trax_account_agreement_write'] = [
                'name' => __('trax-account::common.perm_agreement_write'),
                'class' => Permissions\AgreementWritePermission::class,
            ];
        }

        // Admin Users
        if (config('trax-account.services.users')) {
            $res['trax_account_user_crud'] = [
                'name' => __('trax-account::common.perm_users_management'),
                'class' => Permissions\UserCrudPermission::class,
            ];
        }

        // Admin Groups
        if (config('trax-account.services.groups')) {
            $res['trax_account_group_crud'] = [
                'name' => __('trax-account::common.perm_groups_management'),
                'class' => Permissions\GroupCrudPermission::class,
            ];
        }

        // Admin Roles
        if (config('trax-account.services.roles')) {
            $res['trax_account_role_crud'] = [
                'name' => __('trax-account::common.perm_roles_management'),
                'class' => Permissions\RoleCrudPermission::class,
            ];
        }

        // Admin Entities
        if (config('trax-account.services.entities')) {
            $res['trax_account_entity_crud'] = [
                'name' => __('trax-account::common.perm_entities_management'),
                'class' => Permissions\EntityCrudPermission::class,
            ];
        }

        return $res;
    }

}

