<?php

namespace PaymentAPI\Exception;

use Exception;

class InvalidApiKeyException extends Exception
{
    public function __construct(string $message = "Invalid API key.", int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
