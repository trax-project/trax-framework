<?php

namespace Trax\Account\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordLaravel;
use Illuminate\Notifications\Messages\MailMessage;

class Invitation extends ResetPasswordLaravel
{

    /**
     * Create a notification instance.
     */
    public function __construct($token, $lang)
    {
        app()->setLocale($lang);
        parent::__construct($token);
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->template('trax-notification::mail.template')
            ->subject(__('trax-account::common.invitation'))
            ->greeting(__('trax-notification::common.dear') . ' ' . $notifiable->data->firstname . ',')
            ->line(__('trax-account::notifications.invitation_pre'))
            ->action(__('trax-account::common.login'), url(config('app.url') . route('password.reset', $this->token, false)))
            ->line(__('trax-account::notifications.invitation_post'));
    }
}
