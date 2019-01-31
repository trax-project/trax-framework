<?php

namespace Trax\Notification\Stores;

use Trax\DataStore\Stores\DataStoreFilter;

traxCreateDataStoreSwitchClass('Trax\Notification\Stores', 'trax-notification', 'NotificationUser');

class NotificationUserStore extends NotificationUserStoreSwitch
{
    use DataStoreFilter;

    /**
     * Filters.
     */
    protected $filters = [
        'user_id' => [
            'type' => 'Equal', 'target' => 'user_id'
        ],
    ];

}
