<?php

namespace Trax\Account;

class AccountPermissions extends PermissionsRegistrar
{

    /**
     * Get permissions.
     */
    protected function permissions()
    {
        return [

            // Settings
            'trax_account_basic_client_default' => [
                'class' => Permissions\BasicClientDefaultPermission::class,
                'model' => 'Trax\Account\Models\BasicClient',
            ],
            'trax_account_basic_client_crud' => [
                'name' => __('trax-account::common.perm_basic_clients_management'),
                'class' => Permissions\BasicClientCrudPermission::class,
            ],
            'trax_account_agreement_default' => [
                'class' => Permissions\AgreementDefaultPermission::class,
                'model' => 'Trax\Account\Models\Agreement',
            ],
            'trax_account_agreement_write' => [
                'name' => __('trax-account::common.perm_agreement_write'),
                'class' => Permissions\AgreementWritePermission::class,
            ],

            // Admin
            'trax_account_user_default' => [
                'class' => Permissions\UserDefaultPermission::class,
                'model' => 'Trax\Account\Models\User',
            ],
            'trax_account_user_crud' => [
                'name' => __('trax-account::common.perm_users_management'),
                'class' => Permissions\UserCrudPermission::class,
            ],
            'trax_account_group_default' => [
                'class' => Permissions\GroupDefaultPermission::class,
                'model' => 'Trax\Account\Models\Group',
            ],
            'trax_account_group_crud' => [
                'name' => __('trax-account::common.perm_groups_management'),
                'class' => Permissions\GroupCrudPermission::class,
            ],
            'trax_account_role_default' => [
                'class' => Permissions\RoleDefaultPermission::class,
                'model' => 'Trax\Account\Models\Role',
            ],
            'trax_account_role_crud' => [
                'name' => __('trax-account::common.perm_roles_management'),
                'class' => Permissions\RoleCrudPermission::class,
            ],
            'trax_account_entity_default' => [
                'class' => Permissions\EntityDefaultPermission::class,
                'model' => 'Trax\Account\Models\Entity',
            ],
            'trax_account_entity_crud' => [
                'name' => __('trax-account::common.perm_entities_management'),
                'class' => Permissions\EntityCrudPermission::class,
            ],
        ];
    }

}

