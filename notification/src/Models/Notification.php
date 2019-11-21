<?php

namespace Trax\Notification\Models;

traxCreateModelSwitchClass('Trax\Notification\Models', 'trax-notification', 'Notification');

class Notification extends NotificationModel
{
    
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
        'id', 'data', 'title', 'context', 'styled_context', 'message', 'actionLabel', 'metadata', 'created_at', 'updated_at'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = ['title', 'context', 'styled_context', 'message', 'actionLabel', 'metadata'];


    /**
     * Metadata.
     */
    public function getMetadataAttribute($value)
    {
        return isset($this->data->metadata) ? $this->data->metadata : [];
    }

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
     * Styled Context.
     */
    public function getStyledContextAttribute($value)
    {
        return isset($this->data->styled_context) ? __($this->data->styled_context) : '';
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
