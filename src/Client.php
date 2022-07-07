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
    const HELLIO_MESSAGING_OTP_ENDPOINT = 'https://api.helliomessaging.com/channels/2fa/v3/request';
    const HELLIO_MESSAGING_OTP_VERIFICATION_ENDPOINT = 'https://api.helliomessaging.com/channels/2fa/v3/verify';
    const HELLIO_MESSAGING_SMS_ENDPOINT = 'https://api.helliomessaging.com/channels/sms/v3/send';
    const HELLIO_MESSAGING_BALANCE_ENDPOINT = 'https://api.helliomessaging.com/account/v3/balance';
    const HELLIO_MESSAGING_EMAIL_VALIDATOR_ENDPOINT = 'https://api.helliomessaging.com/channels/email/v3/validator';

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
     * @param int $message_type
     * @param string|null $recipient_email
     * @param string|null $token_length
     * @return bool
     * @throws GuzzleException
     */

    public function otp(string $mobile_number, string $sender_id = null, string $timeout = null, string $token_length = null, string $message = null, int $message_type = 0, string $recipient_email = null): bool
    {
        $guzzle = version_compare(Guzzle::VERSION, '7.2') >= 0;

        $response = $this->http->post(self::HELLIO_MESSAGING_OTP_ENDPOINT, [$guzzle ? 'form_params' : 'body' => ['client_id' => $this->client_id, 'application_secret' => $this->application_secret, 'message' => $message, 'mobile_number' => $mobile_number, 'timeout' => $timeout, 'message_type' => $message_type, 'recipient_email' => $recipient_email, 'token_length' => $token_length, 'sender_id' => $sender_id ?? env('helliomessaging.default_sender')]]);
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
        $guzzle = version_compare(Guzzle::VERSION, '7.2') >= 0;
        $response = $this->http->post(self::HELLIO_MESSAGING_BALANCE_ENDPOINT, [$guzzle ? 'form_params' : 'body' => ['client_id' => $this->client_id, 'application_secret' => $this->application_secret]]);
        if ($response->getStatusCode() === 200) {
            $body = json_decode((string)$response->getBody(), true);
            return isset($body['type']) && ($body['type'] === 'success');
        }
        return 0;
    }


    /**
     * @param array|null $email
     * @param string|null $label
     * @return bool
     * @throws GuzzleException
     */

    public function emailvalidator(array $email, string $label = null): bool
    {
        $guzzle = version_compare(Guzzle::VERSION, '7.2') >= 0;
        $response = $this->http->post(self::HELLIO_MESSAGING_EMAIL_VALIDATOR_ENDPOINT, [$guzzle ? 'form_params' : 'body' => ['client_id' => $this->client_id, 'application_secret' => $this->application_secret, 'email' => $email, 'label' => $label,]]);
        if ($response->getStatusCode() === 200) {
            $body = json_decode((string)$response->getBody(), true);
            return isset($body['type']) && ($body['type'] === 'success');
        }
        return false;
    }

    /**
     * @param array|null $mobile_number
     * @param string|array $message
     * @param string|null $sender_id
     * @param string|null $message_type
     * @return false|mixed
     * @throws GuzzleException
     */
    public function sms(array $mobile_number, $message, string $sender_id = null, string $message_type = null)
    {
        if (is_string($message)) {
            $message = ['message' => $message, 'message_type' => $message_type, 'mobile_number' => (array)$mobile_number];
        }
        $response = $this->http->post(self::HELLIO_MESSAGING_SMS_ENDPOINT, ['headers' => ['client_id' => $this->client_id, 'application_secret' => $this->application_secret], 'json' => ['sender_id' => $sender_id ?? env('helliomessaging.default_sender'), 'message' => $message]]);
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
        $guzzle = version_compare(Guzzle::VERSION, '7.2') >= 0;
        $response = $this->http->post(self::HELLIO_MESSAGING_OTP_VERIFICATION_ENDPOINT, [$guzzle ? 'form_params' : 'body' => ['client_id' => $this->client_id, 'application_secret' => $this->application_secret, 'mobile_number' => $mobile_number, 'token' => $token,]]);
        if ($response->getStatusCode() === 200) {
            $body = json_decode((string)$response->getBody(), true);
            return isset($body['type']) && ($body['type'] === 'success');
        }
        return false;
    }
}
