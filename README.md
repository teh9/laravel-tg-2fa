# Laravel two-factor authentication via Telegram

A simple two-factor implementation using Telegram for Laravel.

![Packagist Downloads](https://img.shields.io/packagist/dt/teh9/laravel2fa)
![Packagist Version](https://img.shields.io/packagist/v/teh9/laravel2fa)
![Packagist License](https://img.shields.io/github/license/teh9/laravel-tg-2fa)

## Installation

You can install the package via composer:
```
composer require teh9/laravel2fa
```

Publish the package config, migrations & localizations files.

```
php artisan vendor:publish --provider="Teh9\Laravel2fa\TelegramTwoFactorServiceProvider"
```

## Usage

### Set up

In your project folder **config/laravel2fa.php**, provide bot api key it can be received by official telegram bot <a href="https://telegram.me/BotFather">@BotFather</a> 

```php 
'api_key' => 'YOUR_BOT_API_KEY'
```

### Migrations

After publishing, you will have migration, execute:

``` 
php artisan migrate
```

Will be added 2 columns for **users** table, if you want change table you can do it in migration file:
**database/migrations/add_two_factor_columns_to_model_table.php**
```
chat_id - big integer|nullable|deafult-null
secret  - string     |nullable|default-null
```

### Languages

2 languages are available in files **/resources/lang/<a href="#">[lang]</a>/2fa.php**:
- en;
- ru;

You can add any else but watch on existed implementations on how to make it correctly

### Prepare Users Model:

```php 
class User extends Model implements TelegramTwoFactor
{
    use HasAuth;
}
```

### Save code in database and send notification with code in telegram

```php 
$user = User::first();
// Might be passed 2 params
// 1-st preffered length of code by default 6
// 2-nd is language by default en
$user->sendCode(4, 'ru'); // return boolean
```

### Validate code

```php 
// The received code from the telegram must be passed to the method, which is described below
$code = 'CODE_FROM_TELEGRAM'; 
$user = User::first();
$user->validateCode($code); // return boolean
```

## License

The MIT License (MIT). Please see <a href="https://github.com/teh9/laravel-tg-2fa/blob/master/LICENSE">License File</a> for more information.

