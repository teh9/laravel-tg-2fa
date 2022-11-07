# Laravel two-factor authentication via Telegram

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

#### Add columns to your user or some other (admin_users) model:
Chat id for you and your users can be found <a href="https://telegram.me/getmyid_bot">here</a>.
```php
$table->bigInteger('chat_id')->nullable()->default(null);
```

```php
$table->string("secret")->nullable()->default(null);
```

### Languages

In **resources/lang/(YOUR_LANG)/auth.php** put in array this (better to use "" instead of ''):
```php 
'2fa' => "Attempt to login! \r\n\r\nConfirmation code: %code%"
//If you want to customize text, somewhere need to place this part: %code% to diplsay code for your user
```

### Prepare User Model:

```php 
class User extends Model implements AuthTwoFactor
{
    use HasAuth;
}
```

### Save code in database and send notification with code in telegram

```php 
$user = User::first();
// Might be passed 2 params
// 1-st is language by default en
// 2-nd preffered length of code by default 6
$user->setSecretCode('ru', 4); // return boolean
```

### Validate code

```php 
// The received code from the telegram must be passed to the method, which is described below
$code = 'CODE_FROM_TELEGRAM'; 
$user = User::first();
$user->verifySecret($code); // return boolean
```

## License

The MIT License (MIT). Please see <a href="https://github.com/teh9/laravel-tg-2fa/blob/master/LICENSE">License File</a> for more information.

