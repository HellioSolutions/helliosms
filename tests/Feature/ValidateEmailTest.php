<?php

namespace Hellio\HellioMessaging\Tests\Feature;

use Hellio\HellioMessaging\Facades\HellioMessaging;


class ValidateEmailTest extends TestCase
{
    /** @test */
    public function it_can_validate_email_address()
    {
        $response = HellioMessaging::emailvalidator([
            'email' => 'support@helliomessaging.com',
            'label' => 'Hellio Marketing Leads',
        ]);

        $this->assertEquals(200, $response->getStatusCode());

    }

}
