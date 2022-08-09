<?php

namespace Hellio\HellioMessaging\Tests\Feature;

use Hellio\HellioMessaging\Facades\HellioMessaging;

class SendSmsTest extends TestCase
{
    /** @test */
    public function it_sends_as_sms()
    {
        $response = HellioMessaging::sms('233242813656', 'The first call assumes that youve already set a default sender_id in the helliomessaging.php config file.');
        $this->assertEquals(200, $response->getStatusCode());
    }
}
