<?php

namespace Trax\Notification\Console;

use Illuminate\Console\Command;

use Trax\Notification\Notifications\DailyDigest;
use TraxAccount;
use TraxNotification;

class DailyDigestCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'notification:daily-digest';

    /**
     * The console command description.
     */
    protected $description = 'Send a daily digest of notifications by email';

    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('');

        // Get subscribers
        if (config('trax-notification.default.digest')) {
            $search = [
                [
                    (object)['key' => 'data.preferences.daily_digest', 'operator' => 'BOOL', 'value' => true],
                    (object)['key' => 'data.preferences.daily_digest', 'operator' => 'NULL']
                ],
                (object)['key' => 'active', 'operator' => 'BOOL', 'value' => true],
            ];
        } else {
            $search = [
                (object)['key' => 'data.preferences.daily_digest', 'operator' => 'BOOL', 'value' => true],
                (object)['key' => 'active', 'operator' => 'BOOL', 'value' => true],
            ];
        }
        $subscribers = TraxAccount::users()->get(['search' => $search]);
        $subscriberIds = $subscribers->pluck('id')->toArray();
        $this->line(count($subscriberIds).' subscribers');

        // Get subscribers with unread messages
        $unread = TraxNotification::notificationUsers()->get(['search' => [
            (object)['key' => 'user_id', 'operator' => 'IN', 'value' => $subscriberIds],
            (object)['key' => 'data.read', 'operator' => 'NULL']
        ]]);
        $userIds = $unread->pluck('user_id')->unique()->toArray();
        $this->line(count($userIds) . ' with unread notifications');

        // Send digests
        $users = $subscribers->whereIn('id', $userIds);
        foreach($users as $user) {

            // Get notifications
            $userNotifications = $unread->where('user_id', $user->id);
            $userNotificationIds = $userNotifications->pluck('notification_id')->toArray();
            $notifications = TraxNotification::notifications()->get(['search' => [
                (object)['key' => 'id', 'operator' => 'IN', 'value' => $userNotificationIds],
            ]]);

            // Send notification
            try {
                (new DailyDigest($user, $notifications))->sendEmail();
                $this->line('Digest sent to ' . $user->fullname . ' (' . count($userNotificationIds) . ' notifications)');
            } catch(\Exception $e) {
                $this->line('Failed to send digest to ' . $user->fullname);
            }
        }
    }

}