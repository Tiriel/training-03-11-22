<?php

namespace App\Tests\Notifier\Factory;

use App\Notifier\Factory\DiscordNotificationFactory;
use App\Notifier\Notifications\DiscordNotification;
use PHPUnit\Framework\TestCase;

class DiscordNotificationFactoryTest extends TestCase
{
    private static DiscordNotificationFactory $factory;

    public static function setUpBeforeClass(): void
    {
        static::$factory = new DiscordNotificationFactory();
    }

    public function testFactoryReturnsDiscordNotification(): DiscordNotification
    {
        $notification = static::$factory->createNotification('Test subject');

        $this->assertInstanceOf(DiscordNotification::class, $notification);

        return $notification;
    }

    /**
     * @depends testFactoryReturnsDiscordNotification
     */
    public function testNotificationSubjectIsSet(DiscordNotification $notification)
    {
        $this->assertSame('Test subject', $notification->getSubject());
    }

    public function testGetDefaultIndexNameReturnsChannelName()
    {
        $this->assertSame('discord', static::$factory::getDefaultIndexName());
    }
}
