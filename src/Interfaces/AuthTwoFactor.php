<?php

namespace Teh9\Laravel2fa\Interfaces;

interface AuthTwoFactor
{
    public function setSecretCode (string $lang = 'en', int $codeLength = 6): bool;

    public function verifySecret (string $code): bool;
}
