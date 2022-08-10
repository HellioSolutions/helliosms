<?php

namespace Hellio\HellioMessaging\Test;

use Hellio\HellioMessaging\Client;
use Hellio\HellioMessaging\Exceptions\CouldNotSendNotification;
use Hellio\HellioMessaging\HellioMessagingChannel;
use Hellio\HellioMessaging\HellioMessagingMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;

class HellioMessagingChannelTest extends TestCase
{
    /** @var Mockery\Mock */
    protected $hellioMessaging;
    protected $channel;

    public function setUp()
    {
        parent::setUp();
        $this->hellioMessaging = Mockery::mock(Client::class);
        $this->channel = new HellioMessagingChannel($this->hellioMessaging);
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Client::class, $this->hellioMessaging);
        $this->assertInstanceOf(HellioMessagingChannel::class, $this->channel);
    }

    /** @test */
    public function it_can_send_sms_notification()
    {
        $this->hellioMessaging->expects('send')
            ->andReturns(200);

        $this->channel->send(new TestNotifiable, new TestNotification);
    }
}

class TestNotifiable
{
    use Notifiable;

    /**
     * @return string
     */
    public function routeNotificationForHellioMessaging(): string
    {
        return '233242813656';
    }
}

class TestNotification extends Notification
{
    /**
     * @param $notifiable
     * @return HellioMessagingMessage
     * @throws CouldNotSendNotification
     */
    public function toHellioMessaging($notifiable): HellioMessagingMessage
    {
        return new HellioMessagingMessage();
    }
}
