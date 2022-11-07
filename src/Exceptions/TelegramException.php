<?php

namespace Teh9\Laravel2fa\Exceptions;

use Exception;

class TelegramException extends Exception
{
    protected $message;

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->message = $message;
    }
}
