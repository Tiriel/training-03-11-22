<?php

namespace App\Notifier\Factory;

use App\Notifier\Notifications\SlackNotification;
use Symfony\Component\Notifier\Notification\Notification;

class SlackNotificationFactory implements NotificationFactoryInterface, IterableFactoryInterface
{

    public function createNotification($message): Notification
    {
        return new SlackNotification($message);
    }

    public static function getDefaultIndexName(): string
    {
        return 'slack';
    }
}