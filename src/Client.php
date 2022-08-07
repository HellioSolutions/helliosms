<?php

namespace Hellio\HellioMessaging;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Hellio\HellioMessaging\Message\MessageType;

/**
 * Class Client
 * @package Hellio\HellioMessaging
 */
class Client
{
    /**
     * @var GuzzleClient
     */

    protected $client;

    /**
     * Response from helliomessaging api
     * @var mixed
     */

    protected $response;

    /**
     * @var string
     */

    protected $baseUrl;

    protected $defaultBody;


    public function __construct()
    {
        $this->baseUrl = config('helliomessaging.baseUrl');

        $clientId = config('helliomessaging.clientId');
        $applicationSecret = config('helliomessaging.applicationSecret');
        $senderId = config('helliomessaging.defaultSender');

        $this->defaultBody = [
            'clientId' => $clientId,
            'authKey' => sha1($clientId . $applicationSecret . date('YmdH')),
            'senderId' => $senderId
        ];

        $this->client = new GuzzleClient(
            [
                'base_uri' => $this->baseUrl,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]
        );
    }

    /**
     * Get customer account balance
     * @return array
     * @throws GuzzleException
     */

    public function getCustomerBalance(): array
    {

        return $this->jsonRequest('GET', '/account/v3/balance');
    }

    /**
     * @throws GuzzleException
     */
    private function jsonRequest($method, $url, $body = [])
    {
        return json_decode($this->client->request($method, $url,
            [
                'body' => json_encode(array_merge($this->defaultBody, $body))
            ])->getBody());
    }

    /**
     * @throws GuzzleException
     */
    public function sms(
        $mobile_number,
        string $message,
        int $message_type = MessageType::SMS
    )
    {
        if (is_array($mobile_number)) {
            $mobile_number = implode($mobile_number, ",");
        }
        $data = [
            'msisdn' => $mobile_number,
            'message' => $message,
            'message_type' => $message_type
        ];
        return $this->jsonRequest('POST', '/v2/sms', $data);
    }

    /**
     * @throws GuzzleException
     */
    public function otp(
        string $mobile_number,
        string $timeout,
        string $token_length,
        string $message,
        int    $message_type = MessageType::SMS,
        string $recipient_email = null
    )
    {

        $data = [
            'msisdn' => $mobile_number,
            'timeout' => $timeout,
            'token_length' => $token_length,
            'message' => $message,
            'message_type' => $message_type,
            'recipient_email' => $recipient_email
        ];

        return $this->jsonRequest('POST', '/channels/2fa/v3/request', $data);
    }

    /**
     * @throws GuzzleException
     */
    public function emailvalidator(
        array  $email,
        string $label = null
    )
    {

        $data = [
            'email' => $email,
            'label' => $label,
        ];


        return $this->jsonRequest('POST', '/channels/email/v3/validator', $data);
    }

    /**
     * @throws GuzzleException
     */
    public function verifyOtp(
        string $mobile_number,
        string $token
    )
    {

        $data = [
            'mobile_number' => $mobile_number,
            'token' => $token,
        ];

        return $this->jsonRequest('POST', 'channels/2fa/v3/verify', $data);
    }
}
