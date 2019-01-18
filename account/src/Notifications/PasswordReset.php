<?php

namespace Trax\Account\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordReset extends Notification
{

    /**
     * Create a notification instance.
     */
    public function __construct($lang)
    {
        app()->setLocale($lang);
    }

    /**
     * Get the notification's channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $loginMessage = config('trax-account.auth.username')
            ? __('trax-account::notifications.password_reset_pre_username', ['username' => $notifiable->username])
            : __('trax-account::notifications.password_reset_pre_email', ['email' => $notifiable->email]);
        
        return (new MailMessage)
            ->template('trax-notification::mail.template')
            ->subject(__('trax-account::common.password_has_been_reset'))
            ->greeting(__('trax-notification::common.dear') . ' ' . $notifiable->data->firstname . ',')
            ->line($loginMessage)
            ->action(__('trax-account::common.login'), url(route('login')))
            ->line(__('trax-account::notifications.password_reset_post'));
    }
}
