<?php

namespace Hellio\HellioMessaging\Test;

use Hellio\HellioMessaging\HellioMessagingMessage;

class HellioMessagingMessageTest extends TestCase
{
    /** @var HellioMessagingMessage */
    protected $message;

    public function setUp(): void
    {
        parent::setUp();
        $this->message = new HellioMessagingMessage();

    }

    /** @test */
    public function it_can_get_the_content()
    {
        $this->message->content('myMessage');
        $this->assertEquals('myMessage', $this->message->getContent());
    }

    /** @test */
    public function it_can_get_the_sender()
    {
        $this->message->from('HellioSMS');
        $this->assertEquals('HellioSMS', $this->message->getSender());
    }

    /** @test */
    public function it_can_get_the_default_sender()
    {
        $this->assertEquals('HellioSMS', $this->message->getSender());
    }
}
