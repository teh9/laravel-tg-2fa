<?php

namespace Teh9\Laravel2fa\Exceptions;

use Exception;

class ChatIdIsNull extends Exception
{
    protected $message = "Telegram user chat id is not provided";
}
