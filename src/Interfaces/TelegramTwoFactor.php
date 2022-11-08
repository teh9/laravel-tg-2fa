<?php

namespace Teh9\Laravel2fa\Interfaces;

interface TelegramTwoFactor
{
    public function setSecretCode (int $codeLength = 6, string $lang = 'en'): bool;

    public function validateCode (string $code): bool;
}
