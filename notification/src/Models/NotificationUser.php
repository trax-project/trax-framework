<?php

namespace Trax\Notification\Models;

traxCreateModelSwitchClass('Trax\Notification\Models', 'trax-notification', 'NotificationUser');

class NotificationUser extends NotificationUserModel
{
    
    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'data' => 'object',
    ];

    /**
     * The table associated with the model.
     */
    protected $table = 'trax_notification_user';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'notification_id', 'user_id', 'data'
    ];

    /**
     * The attributes that should be visible.
     */
    protected $visible = [
        'id', 'notification_id', 'notification', 'user_id', 'data'
    ];

    /**
     * Definition, needed by Trax Data Stores.
     */
    protected $trax = [
        'columns' => ['notification_id', 'user_id'],
        'relations' => [
            'notification' => 'single',
        ],
    ];


    /**
     * Get the notification.
     */
    public function notification()
    {
        return $this->belongsTo('Trax\Notification\Models\Notification');
    }


}
