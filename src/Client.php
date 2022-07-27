<?php

namespace HellioSolutions\HellioMessaging;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class Client
 * @package HellioSolutions\HellioMessaging
 */
class Client
{
    const BASE_URL = 'https://api.helliomessaging.com/';

    /**
     * @var Guzzle
     */
    protected $http;

    /**
     * @var string
     */
    protected $client_id;

    /**
     * @var string
     */
    protected $application_secret;

    /**
     * Client constructor.
     * @param string $client_id
     * @param string $application_secret
     */
    public function __construct(string $client_id, string $application_secret)
    {
        $this->http = new Guzzle(['http_errors' => false]);
        $this->client_id = $client_id;
        $this->application_secret = $application_secret;
    }

    /**
     * @param string $mobile_number
     * @param string|null $sender_id
     * @param string|null $message
     * @param string|null $timeout
     * @param string $message_type
     * @param string|null $recipient_email
     * @param string|null $token_length
     * @return bool
     * @throws GuzzleException
     */

    public function otp(
        string $mobile_number,
        string $sender_id = null,
        string $timeout = null,
        string $token_length = null,
        string $message = null,
        int $message_type = 0,
        string $recipient_email = null): bool
    {

        $response = $this->http->post(self::BASE_URL . 'channels/2fa/v3/request',  ['message' => $message, 'mobile_number' => $mobile_number, 'timeout' => $timeout, 'message_type' => $message_type, 'recipient_email' => $recipient_email, 'token_length' => $token_length, 'sender_id' => $sender_id ?? env('helliomessaging.default_sender')]);
        if ($response->getStatusCode() === 200) {
            $body = json_decode((string)$response->getBody(), true);
            return isset($body['type']) && ($body['type'] === 'success');
        }
        return false;
    }


    /**
     * @throws GuzzleException
     */

    public function balance(): bool
    {
        $response = $this->http->post(self::BASE_URL . '/account/v3/balance');
        if ($response->getStatusCode() === 200) {
            $body = json_decode((string)$response->getBody(), true);
            return isset($body['type']) && ($body['type'] === 'success');
        }
        return 0;
    }


    /**
     * @param array $email
     * @param string|null $label
     * @return bool
     * @throws GuzzleException
     */

    public function emailvalidator(array $email, string $label = null): bool
    {

        $response = $this->http->post(self::BASE_URL . '/channels/email/v3/validator', ['email' => $email, 'label' => $label]);
        if ($response->getStatusCode() === 200) {
            $body = json_decode((string)$response->getBody(), true);
            return isset($body['type']) && ($body['type'] === 'success');
        }
        return false;
    }

    /**
     * @param array|string $mobile_number
     * @param string|array $message
     * @param string $sender_id
     * @param string $message_type
     * @return false|mixed
     * @throws GuzzleException
     */
    public function sms(
        array $mobile_number,
        $message,
        string $sender_id = null,
        string $message_type = null)
    {
        $response = $this->http->post(self::BASE_URL . '/channels/sms/v3/send', ['sender_id' => $sender_id ?? env('helliomessaging.default_sender'), 'message' => $message, 'mobile_number' => $mobile_number, 'message_type' => $message_type]);
        if ($response->getStatusCode() === 200) {
            $body = json_decode((string)$response->getBody(), true);
            return isset($body['type']) && ($body['type'] === 'success') ? $body['message'] : false;
        }
        return false;
    }

    /**
     * @param string $mobile_number
     * @param string $token
     * @return bool
     * @throws GuzzleException
     */
    public function verify(string $mobile_number, string $token): bool
    {
        return $this->client->makeRequest(self::BASE_URL . 'channels/2fa/v3/verify', ['mobile_number' => $mobile_number, 'token' => $token]);
    }

        /**
     * @param array|null $body
     * @param string $url
     * @return bool
     */

    public function makeRequest($url, $body): bool
    {
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->http->sendAsync->post($url, ['headers' => $headers, ['body' => ['client_id' => $this->client_id, 'application_secret' => $this->application_secret] + $body ?: null ]])->wait();
        if ($response->getStatusCode() === 200) {
            $body = json_decode((string)$response->getBody(), true);
            return isset($body['type']) && ($body['type'] === 'success');
        }
        return false;
    }
}
