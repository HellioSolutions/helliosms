<?php

namespace Hellio\HellioMessaging\Tests\Feature;

use Hellio\HellioMessaging\Facades\HellioMessaging;


class VerifyOtpTest extends TestCase
{
    /** @test */
    public function it_can_verify_otp()
    {
        $response = HellioMessaging::verifyotp('233242813656', '02255');
        $this->assertEquals(200, $response->getStatusCode());
    }
}
