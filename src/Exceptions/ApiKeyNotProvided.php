<?php

namespace Teh9\Laravel2fa\Exceptions;

use Exception;

class ApiKeyNotProvided extends Exception
{
    protected $message = 'Telegram API key is not provided';
}
