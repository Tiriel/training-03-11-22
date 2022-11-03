<?php

namespace App\Tests\Notifier\Factory;

use App\Notifier\Factory\FirebaseNotificationFactory;
use App\Notifier\Notifications\FirebaseNotification;
use PHPUnit\Framework\TestCase;

class FirebaseNotificationFactoryTest extends TestCase
{
    private static FirebaseNotificationFactory $factory;

    public static function setUpBeforeClass(): void
    {
        static::$factory = new FirebaseNotificationFactory();
    }

    public function testFactoryReturnsNullAndThrows()
    {
        $this->expectError();
        $notification = static::$factory->createNotification('Test subject');

        $this->assertNull($notification);
    }

    public function testGetDefaultIndexNameReturnsChannelName()
    {
        $this->assertSame('firebase', static::$factory::getDefaultIndexName());
    }
}
