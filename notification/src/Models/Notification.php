<?php

namespace Trax\Notification\Models;

traxCreateModelSwitchClass('Trax\Notification\Models', 'trax-notification', 'Notification');

class Notification extends NotificationModel
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
    protected $table = 'trax_notification';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'data'
    ];

    /**
     * The attributes that should be visible.
     */
    protected $visible = [
        'id', 'data', 'title', 'context', 'message', 'actionLabel', 'created_at', 'updated_at'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = ['title', 'context', 'message', 'actionLabel'];


    /**
     * Title.
     */
    public function getTitleAttribute($value)
    {
        return isset($this->data->title) ? __($this->data->title) : '';
    }

    /**
     * Context.
     */
    public function getContextAttribute($value)
    {
        return isset($this->data->context) ? __($this->data->context) : '';
    }

    /**
     * Message.
     */
    public function getMessageAttribute($value)
    {
        return isset($this->data->message) ? __($this->data->message) : '';
    }

    /**
     * Action label.
     */
    public function getActionLabelAttribute($value)
    {
        return isset($this->data->action) && isset($this->data->action->label) ? __($this->data->action->label) : '';
    }

    /**
     * Get the users.
     */
    public function users()
    {
        return $this->belongsToMany('Trax\Account\Models\User', 'trax_notification_user');
    }


}
