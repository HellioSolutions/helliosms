<?php

namespace HellioSolutions\HellioMessaging\Test;

use Mockery;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->hellioMessaging = Mockery::mock(Client::class);
        $this->channel = new HellioMessagingChannel($this->hellioMessaging);
    }

}
