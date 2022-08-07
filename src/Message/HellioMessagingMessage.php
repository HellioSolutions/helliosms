<?php

namespace Hellio\HellioMessaging\Message;


class HellioMessagingMessage
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
     * @var string
     */
    public $mobile_number;

    /**
     * @param string $message
     * @return $this
     */
    public function message(string $message): HellioMessagingMessage
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param string $message_type
     * @return $this
     */
    public function message_type(string $message_type): HellioMessagingMessage
    {
        $this->message_type = $message_type;
        return $this;
    }


    /**
     * @param string $sender_id
     * @return $this
     */
    public function sender_id(string $sender_id): HellioMessagingMessage
    {
        $this->sender_id = $sender_id;
        return $this;
    }

    public function mobile_number(string $mobile_number): HellioMessagingMessage
    {
        $this->mobile_number = $mobile_number;
        return $this;
    }


}
