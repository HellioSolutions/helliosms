<?php

namespace HellioSolutions\HellioMessaging\Channels;

use GuzzleHttp\Exception\GuzzleException;
use HellioSolutions\HellioMessaging\Client;
use HellioSolutions\HellioMessaging\Message\HellioMessagingMessage;
use Illuminate\Notifications\Notification;

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
     * @throws GuzzleException
     */
    public function send($notifiable, Notification $notification)
    {
        $mobile_number = $notifiable->routeNotificationFor('helliomessaging', $notification);
        if (empty($mobile_number)) {
            return;
        }
        /** @var HellioMessagingMessage $message */
        $message = $notification->toHellioMessaging($notifiable);
        $this->client->sms($mobile_number, $message->message, $message->sender_id, $message->message_type);
    }
}
