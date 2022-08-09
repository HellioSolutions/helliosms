<?php

namespace Hellio\HellioMessaging;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Hellio\HellioMessaging\Message\MessageType;

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

        //API v1 default params
        $username = config('helliomessaging.username');
        $password = config('helliomessaging.password');
        $senderId = config('helliomessaging.defaultSender');

        if (config('helliomessaging.apiVersion') == 'v1') {
            $this->defaultBody = [
                'username' => $username,
                'password' => $password,
                'sender' => $senderId,
            ];
        } else {
            $this->defaultBody = [
                'client_id' => $clientId,
                'authKey' => sha1($clientId . $applicationSecret . date('YmdH')),
                'sender_id' => $senderId
            ];
        }

        $this->client = new GuzzleClient(
            [
                'verify' => false, // disable ssl verification
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

    public function getCustomerBalance()
    {
       if (config('helliomessaging.apiVersion') == 'v1') {
              $url = config('helliomessaging.apiVersion') . '/credit-balance';
         } else {
                $url = 'v3/customer/balance';
         }
        return $this->jsonRequest('GET', $url);
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
        ?string $senderId = null,
        string $message_type = MessageType::SMS
    )
    {
        if (is_array($mobile_number)) {
            $mobile_number = implode($mobile_number, ",");
        }
        $data = [
            'msisdn' => $mobile_number,
            'message' => $message,
            'message_type' => $message_type,
            'sender_id' => $senderId ?? config('helliomessaging.defaultSender')
        ];
        return $this->jsonRequest('POST', '/v2/sms', $data);
    }

    /**
     * @throws GuzzleException
     */
    public function otp(
        string $mobile_number,
        string $timeout,
        ?string $senderId = null,
        string $token_length,
        string $message,
        string $message_type = MessageType::SMS,
        string $recipient_email = null
    )
    {

        $data = [
            'msisdn' => $mobile_number,
            'timeout' => $timeout,
            'token_length' => $token_length,
            'message' => $message,
            'sender_id' => $senderId ?? config('helliomessaging.defaultSender'),
            'message_type' => $message_type,
            'recipient_email' => $recipient_email
        ];

        return $this->jsonRequest('POST', '/channels/2fa/v3/request', $data);
    }

    /**
     * @throws GuzzleException
     */
    public function emailvalidator(
         $email,
        string $label = null
    )
    {
        if (is_array($email)) {
            $email = implode($email, ",");
        }

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
