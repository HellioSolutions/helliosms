<?php

namespace Hellio\HellioMessaging\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Facade
 * @package Hellio\HellioMessaging
 *
 * @method static bool otp(string $mobile_number, string $sender_id = null, string $message = null, $otp_length = null, $timeout = null, $message_type = null, $recipient_email = null)
 * @method static string|false sms(string|array|null $mobile_number, string|array $message, string $sender_id = null, string $message_type = null)
 * @method static string|false voice(string|array|null $mobile_number, string|array $message, string $sender_id = null, string $message_type = null)
 * @method static string|false emailvalidator(?array $email, string $label = null)
 * @method static string|false balance()
 * @method static bool verify(string $mobile_number, string $token)
 */
class HellioMessaging extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor(): string
    {
        return 'helliomessaging';
    }
}
