<?php

namespace App\Notifier\Factory;

use RuntimeException;
use Symfony\Component\Notifier\Notification\Notification;

class ChainNotificationFactory implements NotificationFactoryInterface
{
    public function __construct(
        private iterable $factories
    ){
        /** @var iterable|NotificationFactoryInterface[] $factories */
        $this->factories =  $this->factories instanceof \Traversable ? iterator_to_array($this->factories) : $this->factories;
    }

    public function createNotification($message, $channel = ''): Notification
    {
        if (!array_key_exists($channel, (array) $this->factories)) {
            throw new RuntimeException();
        }

        return $this->factories[$channel]->createNotification($message);
    }
}