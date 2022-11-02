<?php

namespace App\Notifier;

use App\Entity\User;
use App\Notifier\Factory\ChainNotificationFactory;
use App\Notifier\Factory\NotificationFactoryInterface;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class NotificationHandler
{
    public function __construct(
        private ChainNotificationFactory $factory,
        private NotifierInterface $notifier
    ){}

    public function sendNotification(string $channel, string $message, array|User $users)
    {
        $users = is_iterable($users) ? $users : [$users];

        $recipients = [];
        foreach ($users as $user) {
            $recipients[] = new Recipient($user->getEmail());
        }

        $notification = $this->factory->createNotification($message, $channel);
        $this->notifier->send($notification, ...$recipients);
    }
}