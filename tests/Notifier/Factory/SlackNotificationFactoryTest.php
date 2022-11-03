<?php

namespace App\Tests\Notifier\Factory;

use App\Notifier\Factory\SlackNotificationFactory;
use App\Notifier\Notifications\SlackNotification;
use PHPUnit\Framework\TestCase;

class SlackNotificationFactoryTest extends TestCase
{
    private static SlackNotificationFactory $factory;

    public static function setUpBeforeClass(): void
    {
        static::$factory = new SlackNotificationFactory();
    }

    public function testFactoryReturnsSlackNotification(): SlackNotification
    {
        $notification = static::$factory->createNotification('Test subject');

        $this->assertInstanceOf(SlackNotification::class, $notification);

        return $notification;
    }

    /**
     * @depends testFactoryReturnsSlackNotification
     */
    public function testNotificationSubjectIsSet(SlackNotification $notification)
    {
        $this->assertSame('Test subject', $notification->getSubject());
    }

    public function testGetDefaultIndexNameReturnsChannelName()
    {
        $this->assertSame('slack', static::$factory::getDefaultIndexName());
    }
}
