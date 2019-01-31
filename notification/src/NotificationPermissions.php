<?php

namespace Trax\Notification;

use Trax\Account\PermissionsRegistrar;

class NotificationPermissions extends PermissionsRegistrar
{
    
    /**
     * Get permissions.
     */
    protected function permissions()
    {
        return [
            'trax_notification_user_default' => [
                'class' => Permissions\NotificationUserDefaultPermission::class,
                'model' => 'Trax\Notification\Models\NotificationUser',
            ],

        ];
    }

}

