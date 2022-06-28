<?php

namespace HellioSolutions\HellioMessaging\Channels;

use Illuminate\Notifications\Notification;
use HellioSolutions\HellioMessaging\Message\HellioMessagingSMS;
use HellioSolutions\HellioMessaging\Client;

/**
 * Class HellioMessagingChannel
 * @package HellioSolutions\HellioMessaging\Channels
 */
class HellioMessagingChannel
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * HellioMessagingChannel constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification): void
    {
        $mobile_number = $notifiable->routeNotificationFor('helliomessaging', $notification);
        if (empty($mobile_number)) {
            return;
        }
        /** @var HellioMessagingSMS $message */
        $message = $notification->toHellioMessaging($notifiable);
        $this->client->sms($mobile_number, $message->message, $message->sender_id, $message->message_type);
    }
}
