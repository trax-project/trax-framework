<?php

namespace Trax\Account\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordDefined extends Notification
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
            ? __('trax-account::notifications.password_defined_pre_username', ['username' => $notifiable->username])
            : __('trax-account::notifications.password_defined_pre_email', ['email' => $notifiable->email]);
        
        return (new MailMessage)
            ->template('trax-notification::mail.template')
            ->subject(__('trax-account::common.password_defined'))
            ->greeting(__('trax-notification::common.dear') . ' ' . $notifiable->jsonData()->firstname . ',')
            ->line($loginMessage)
            ->action(__('trax-account::common.login'), url(route('login')))
            ->line(__('trax-account::notifications.password_defined_post'));
    }
}
