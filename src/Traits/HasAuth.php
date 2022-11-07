<?php

namespace Teh9\Laravel2fa\Traits;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Teh9\Laravel2fa\Exceptions\ChatIdIsNull;
use Teh9\Laravel2fa\Exceptions\SecretIsNull;
use Teh9\Laravel2fa\Exceptions\TelegramException;

trait HasAuth
{
    /**
     * @var string
     */
    private string $code;

    /**
     * @var string
     */
    private string $language;

    public function setSecretCode (string $lang = 'en', int $codeLength = 6): bool
    {
        $this->language = $lang;

        $this->throwExceptionIfChatIdIsNull();

        $this->throwExceptionIfSecretIsNull();

        $this->forceFill([
            'secret' => Hash::make($this->generateSecretCode($codeLength))
        ])->save();

        return $this->sendNotification();
    }

    public function verifySecret (string $code): bool
    {
        if (Hash::check($code, $this->secret)) {
            $this->forceFill([
                'secret' => null
            ])->save();

            return true;
        };

        return false;
    }

    public function throwExceptionIfSecretIsNull ()
    {
        if (is_null($this->secret)) {
            throw new SecretIsNull();
        }
    }

    public function throwExceptionIfChatIdIsNull ()
    {
        if (is_null($this->chat_id)) {
            throw new ChatIdIsNull();
        }
    }

    private function generateSecretCode (int $codeLength): string
    {
        return $this->code = strtoupper(Str::random($codeLength));
    }

    private function sendNotification ()
    {
        $apiKey = config('auth.api_key');
        $post = Http::post('https://api.telegram.org/bot'. $apiKey .'/sendMessage', [
            'chat_id' => $this->chat_id,
            'text'    => $this->getText()
        ]);

        return $this->verifyResponse($post);
    }

    private function getText ()
    {
        App::setlocale($this->language);

        return str_replace('%code%', $this->code, trans('auth.2fa'));
    }

    private function parseResponse (string $response): array
    {
        return json_decode($response, true);
    }

    private function verifyResponse (object $data)
    {
        $response = $this->parseResponse($data);

        if (!$response['ok']) {
            throw new TelegramException($response['description']);
        }

        return true;
    }
}
