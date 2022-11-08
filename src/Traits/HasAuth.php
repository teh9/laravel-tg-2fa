<?php

namespace Teh9\Laravel2fa\Traits;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Teh9\Laravel2fa\Exceptions\ApiKeyNotProvided;
use Teh9\Laravel2fa\Exceptions\ChatIdIsNull;
use Teh9\Laravel2fa\Exceptions\SecretIsNull;
use Teh9\Laravel2fa\Exceptions\TelegramException;

trait HasAuth
{
    /**
     * @var string
     */
    private string $apiKey;

    /**
     * @var string
     */
    private string $code;

    /**
     * @var string
     */
    private string $language;

    /**
     * @throws TelegramException
     * @throws ChatIdIsNull
     * @throws SecretIsNull
     * @throws ApiKeyNotProvided
     */
    public function sendCode (int $codeLength = 6, string $lang = 'en'): bool
    {
        $this->language = $lang;

        $this->throwExceptionIfChatIdIsNull();

        $this->throwExceptionIfSecretIsNull();

        $this->forceFill([
            'secret' => Hash::make($this->generateSecretCode($codeLength))
        ])->save();

        return $this->sendNotification();
    }

    public function validateCode (string $code): bool
    {
        if (Hash::check($code, $this->secret)) {
            $this->forceFill(['secret' => null])->save();

            return true;
        }

        return false;
    }

    /**
     * @throws SecretIsNull
     */
    public function throwExceptionIfSecretIsNull ()
    {
        Schema::hasColumn($this->getTable(), 'secret') ?: throw new SecretIsNull();
    }

    /**
     * @throws ChatIdIsNull
     */
    public function throwExceptionIfChatIdIsNull ()
    {
        if (is_null($this->chat_id)) {
            throw new ChatIdIsNull();
        }
    }

    /**
     * @throws ApiKeyNotProvided
     */
    public function throwExceptionNoApiKey ()
    {
        $this->apiKey = config('laravel2fa.api_key');

        if (empty($this->apiKey)) {
            throw new ApiKeyNotProvided();
        }
    }

    private function generateSecretCode (int $codeLength): string
    {
        return $this->code = strtoupper(Str::random($codeLength));
    }

    /**
     * @throws ApiKeyNotProvided
     * @throws TelegramException
     */
    private function sendNotification (): bool
    {
        $this->throwExceptionNoApiKey();

        $post = Http::post('https://api.telegram.org/bot'. $this->apiKey .'/sendMessage', [
            'chat_id' => $this->chat_id,
            'text'    => $this->getText()
        ]);

        return $this->verifyResponse($post);
    }

    private function getText (): string
    {
        App::setlocale($this->language);

        return str_replace('%code%', $this->code, trans('2fa.login_attempt'));
    }

    private function parseResponse (string $response): array
    {
        return json_decode($response, true);
    }

    /**
     * @throws TelegramException
     */
    private function verifyResponse (object $data): bool
    {
        $response = $this->parseResponse($data);

        if (!$response['ok']) {
            throw new TelegramException($response['description']);
        }

        return true;
    }
}
