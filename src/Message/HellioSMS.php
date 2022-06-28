<?php

namespace HellioSolutions\HellioMessaging\Message;

/**
 * Class HellioMessagingSMS
 * @package HellioSolutions\HellioMessaging\Message
 */
class HellioSMS
{
    /**
     * @var string
     */
    public $message_type;

    /**
     * @var string
     */
    public $message;

    /**
     * @var string
     */
    public $sender_id;

    /**
     * @param string $message
     * @return $this
     */
    public function message(string $message): HellioSMS
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param string $message_type
     * @return $this
     */
    public function message_type(string $message_type): HellioSMS
    {
        $this->message_type = $message_type;
        return $this;
    }


    /**
     * @param string $sender_id
     * @return $this
     */
    public function sender_id(string $sender_id): HellioSMS
    {
        $this->sender_id = $sender_id;
        return $this;
    }

}
