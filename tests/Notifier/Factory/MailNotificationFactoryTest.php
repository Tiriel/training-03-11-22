<?php

namespace App\Tests\Notifier\Factory;

use App\Notifier\Factory\MailNotificationFactory;
use App\Notifier\Notifications\MailNotification;
use PHPUnit\Framework\TestCase;

class MailNotificationFactoryTest extends TestCase
{
    private static MailNotificationFactory $factory;

    public static function setUpBeforeClass(): void
    {
        static::$factory = new MailNotificationFactory();
    }

    public function testFactoryReturnsMailNotification(): MailNotification
    {
        $notification = static::$factory->createNotification('Test subject');

        $this->assertInstanceOf(MailNotification::class, $notification);

        return $notification;
    }

    /**
     * @depends testFactoryReturnsMailNotification
     */
    public function testNotificationSubjectIsSet(MailNotification $notification)
    {
        $this->assertSame('Test subject', $notification->getSubject());
    }

    public function testGetDefaultIndexNameReturnsChannelName()
    {
        $this->assertSame('mail', static::$factory::getDefaultIndexName());
    }
}
