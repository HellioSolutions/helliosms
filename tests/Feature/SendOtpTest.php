<?php

namespace Hellio\HellioMessaging\Tests\Feature;

use Hellio\HellioMessaging\Facades\HellioMessaging;


class SendOtpTest extends TestCase
{
    /** @test */
    public function it_can_sends_otp()
    {
        $response = HellioMessaging::otp(
            '233242813656',
            '10',
            '6',
            'Your OTP is: 58855',
            'HellioOTP',
            'support@helliomessaging.com'
        );
        $this->assertEquals(200, $response->getStatusCode());
    }
}
