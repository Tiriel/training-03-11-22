<?php

namespace App\Notifier\Factory;

use Symfony\Component\Notifier\Notification\Notification;

class FirebaseNotificationFactory implements NotificationFactoryInterface, IterableFactoryInterface
{

    public function createNotification($message): Notification
    {
        // TODO: Implement createNotification() method.
    }

    public static function getDefaultIndexName(): string
    {
        return 'firebase';
    }
}