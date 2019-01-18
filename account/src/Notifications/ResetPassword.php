<?php

namespace Trax\Account\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordLaravel;

use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends ResetPasswordLaravel
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
            ->subject(__('trax-account::common.password_reset'))
            ->greeting(__('trax-notification::common.dear') . ' ' . $notifiable->data->firstname . ',')
            ->line(__('trax-account::notifications.reset_password_pre'))
            ->action(__('trax-account::common.reset_password'), url(config('app.url').route('password.reset', $this->token, false)))
            ->line(__('trax-account::notifications.reset_password_post'));
    }
}
