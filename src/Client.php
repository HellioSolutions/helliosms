<?php

namespace Hellio\HellioMessaging;

/**
 * Class Client
 * @package Hellio\HellioMessaging
 */
class Client
{
    /**
     * @var string
     */

    protected $clientId;

    /**
     * @var string
     */

    protected $applicationSecret;

    /**
     * @var string
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


    public function __construct()
    {
        $this->getKey();
        $this->getBaseUrl();
        $this->setRequestOptions();
    }

    /**
     * Get secret key from helliomessaging cofig
     */

    public function getKey()
    {
        $this->clientId = Config::get('helliomessaging.clientId');
        $this->applicationSecret = Config::get('helliomessaging.applicationSecret');
    }

    /**
     * Get base url from helliomessaging config
     */

    public function getBaseUrl()
    {
        $this->baseUrl = Config::get('helliomessaging.baseUrl');
    }

    /**
     * Set request options
     * @return client
     */

    private function setRequestOptions(): Client
    {

        $authBearer = ['auth' => [$this->clientId, $this->applicationSecret]];

        $this->client = new Client(
            [
                'base_uri' => $this->baseUrl,
                'headers' => [
                    'Authorization' => $authBearer,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]
        );

        return $this;
    }

    /**
     * Get custmer account balance
     * @return array
     */

    public function getCustomerBalance(): array
    {

        return $this->setRequestOptions()->setHttpResponse('/account/v3/balance', 'GET', [])->getData();

    }

    /**
     * Get the data response from a get operation
     * @return array
     */
    private function getData(): array
    {
        return $this->getResponse()['data'];
    }

    /**
     * Decode json response into an array
     * @return array
     */

    private function getResponse(): array
    {
        return json_decode($this->response->getBody(), true);
    }

    /**
     * Set http response
     * @param string $url
     * @param string|null $method
     * @param array $body
     * @return Client
     * @throws MethodNotAllowedException
     */

    private function setHttpResponse(string $url, string $method = null, array $body = []): Client
    {
        if (is_null($method)) {
            throw new MethodNotAllowedException("Empty method not allowed");
        }

        $this->response = $this->client->{strtolower($method)}(
            $this->baseUrl . $url,
            ["body" => json_encode($body)]
        );

        return $this;
    }

    public function sms(
        array  $mobile_number,
        string $message,
        string $message_type
    ): array
    {

        $data = [
            'mobile_number' => $mobile_number,
            'message' => $message,
            'message_type' => $message_type,
            'sender_id' => Config::get('helliomessaging.senderId'),
        ];

        return $this->setRequestOptions()->setHttpResponse('/channels/sms/v3/send', 'POST', $data)->getData();

    }

    public function otp(
        string $mobile_number,
        string $timeout,
        string $token_length,
        string $message,
        int    $message_type = 0,
        string $recipient_email = null
    ): array
    {

        $data = [

            'mobile_number' => $mobile_number,
            'timeout' => $timeout,
            'token_length' => $token_length,
            'message' => $message,
            'message_type' => $message_type,
            'recipient_email' => $recipient_email,
            'sender_id' => Config::get('helliomessaging.senderId'),
        ];

        return $this->setRequestOptions()->setHttpResponse('/channels/2fa/v3/request', 'POST', $data)->getData();

    }

    public function emailvalidator(
        array  $email,
        string $label = null
    ): array
    {

        $data = [
            'email' => $email,
            'label' => $label,
        ];


        return $this->setRequestOptions()->setHttpResponse('/channels/email/v3/validator', 'POST', $data)->getData();

    }

    public function verifyOtp(
        string $mobile_number,
        string $token
    ): array
    {

        $data = [
            'mobile_number' => $mobile_number,
            'token' => $token,
        ];

        return $this->setRequestOptions()->setHttpResponse('channels/2fa/v3/verify', 'POST', $data)->getData();

    }

}
