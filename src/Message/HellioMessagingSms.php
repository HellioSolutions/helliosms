<?php

namespace Hellio\HellioMessaging\Message;


class HellioMessagingSms
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
    public function message(string $message): HellioMessagingSms
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param string $message_type
     * @return $this
     */
    public function message_type(string $message_type): HellioMessagingSms
    {
        $this->message_type = $message_type;
        return $this;
    }


    /**
     * @param string $sender_id
     * @return $this
     */
    public function sender_id(string $sender_id): HellioMessagingSms
    {
        $this->sender_id = $sender_id;
        return $this;
    }

    public function mobile_number(string $mobile_number): HellioMessagingSms
    {
        $this->mobile_number = $mobile_number;
        return $this;
    }


}
