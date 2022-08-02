<?php

namespace Hellio\HellioMessaging;

use Exception;

class HellioMessagingException extends Exception
{

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
