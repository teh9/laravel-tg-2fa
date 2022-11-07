<?php

namespace Teh9\Laravel2fa\Exceptions;

use Exception;

class SecretIsNull extends Exception
{
    protected $message = 'Secret column is not created';
}
