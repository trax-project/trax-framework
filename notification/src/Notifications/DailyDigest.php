<?php

namespace Trax\Notification\Notifications;

use Illuminate\Notifications\Notification as NativeNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Notification as NotificationService;
use App;

class DailyDigest extends NativeNotification
{
    /**
     * User.
     */
    protected $user;

    /**
     * Notifications.
     */
    protected $notifications;


    /**
     * Construct.
     */
    public function __construct($user, $notifications)
    {
        $this->user = $user;
        $this->notifications = $notifications;
    }

    /**
     * Send the notification.
     */
    public function sendEmail()
    {
        NotificationService::send([$this->user], $this);
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        App::setLocale($notifiable->lang);
        return (new MailMessage)
            ->markdown('trax-notification::mail.digest', [
                'notifications' => $this->notifications,
            ])
            ->subject(__('trax-notification::common.daily_digest_title'))
            ->greeting(__('trax-notification::common.dear') . ' ' . $notifiable->jsonData()->firstname . ',')
            ->line(__('trax-notification::common.daily_digest_pre', ['count' => $this->notifications->count()]))
            ->action(__('trax-account::common.login'), url(route('login')))
            ->line(__('trax-notification::common.daily_digest_post', ['count' => $this->notifications->count()]))
            ;
    }

}