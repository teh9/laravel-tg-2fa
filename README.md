## Laravel two-factor authentication via Telegram

A simple two-factor implementation using Telegram.

## Installation

Package may be installed via composer
```
composer require teh9/laravel2fa
```

## Usage

### Set up

In your project folder **config/auth.php**, provide bot api key it can be received by official telegram bot <a href="https://telegram.me/BotFather">@BotFather</a> 

```php 
'api_key' => 'YOUR_BOT_API_KEY'
```

### Migrations

Add columns to your user or some other (admin_users) model:
```php
$table->bigInteger('chat_id')->nullable()->default(null);
$table->string("secret")->nullable()->default(null);
```

Prepare User Model:

```php 
Class User extends Model implements AuthTwoFactor
{
    use HasAuth;
}
```

### Save code in database and send notification with code telegram

```php 
$user = User::first();
// Might be passed 2 params
// 1-st is language by default en
// 2-nd preffered length of code by default 6
$user->setSecretCode('ru', 4); // return boolean
```

### Validate code

```php 
$code = 'CODE_FROM_TELEGRAM'; // The received code from the telegram must be passed to the method, which is described below
$user = User::first();
$user->verifySecret($code); // return boolean
```
