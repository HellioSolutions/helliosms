# Laravel Hellio Messaging - Hellio Messaging Integration For Laravel

### About
This package integrates [Hellio Messaging](https://helliomessaging.com)'s SMS solution nicely with [Laravel](https://laravel.com/) 5+ adding support for **Notification** & **Validator** as well.

### Registration
[Sign up](https://app.helliomessaging.com/try-hellio) for Hellio Messaging and get the auth key from your account. You can find the `client_id` and `application_secret` from `Profile Settings > API Keys & Webhooks` key after signing in.

### Installation
```bash
composer require VimKanzoGH/laravel-helliomessaging
```

#### Laravel < 5.5
Once the package is installed, open your `app/config/app.php` configuration file and locate the `providers` key. Add the following line to the end:

```php
HellioSolutions\HellioMessaging\ServiceProvider::class
```

Next, locate the `aliases` key and add the following line:

```php
'HellioMessaging' => HellioSolutions\HellioMessaging\Facade::class,
```

### Configuration
Put the credentials and preferences in ENV with the keys `HELLIO_MESSAGING_CLIENT_ID`, `HELLIO_MESSAGING_APPLICATION_SECRET`, `HELLIO_MESSAGING_DEFAULT_SENDER`. If you want to customize this, publish the default configuration which will create a config file `config/helliomessaging.php`.

```bash
$ php artisan vendor:publish
```

### Usage

## Basic
- Send an SMS to one or more mobile numbers.
```php
<?php

$result = HellioMessaging::sms('233242813656', 'Hello there!');
 
$result = HellioMessaging::sms('233242813656', 'Hello there!', 'HellioSMS');
 
$result = HellioMessaging::sms(null, [
    ['mobile_number' => ['233242813656', '233591451609'], 'message' => 'Hello there!'],
    ['mobile_number' => ['233203555816'], 'message' => 'Come here!'],
], 'HellioSMS');
```

- Send OTP to a mobile number.
```php
<?php

$result = HellioMessaging::otp('233242813656');
   
$result = HellioMessaging::otp('233242813656', 'HellioSMS');
   
$result = HellioMessaging::otp('233242813656', 'HellioSMS', '##OTP## is your OTP, Please dont share it with anyone.');
```

- Verify OTP sent to a mobile number.
```php
<?php

$result = HellioMessaging::verify('233242813656', 1290); // returns true or false
```

## Notification
Include `helliomessaging` in your notification's channels:
```php
<?php

/**
 * @param  mixed  $notifiable
 * @return array
 */
public function via($notifiable)
{
    return ['helliomessaging'];
}
```

Define the `toHellioMessaging` method:

```php
<?php

use HellioSolutions\HellioMessaging\Message\HellioMessagingSMS;

public function toHellioMessaging()
{
    return (new HellioMessagingSMS)
        ->message(__('This is just a test message.'))
	    ->sender_id('HellioSMS') // [Optional] - Will pick default sender ID from HELLIO_MESSAGING_DEFAULT_SENDER or if not set, will use the application name.
        ->to('233242813656');
}
```

Default `routeNotificationForHellioMessaging` method in your notifiable class:
```php
<?php

public function routeNotificationForHellioMessaging($notification)
{
    return $this->mobile_number;
}
```

Finally, send the notification:
```php
<?php

$notifiable = /* some class */
$notifiable->notify(new App\Notifications\HellioMessagingTestNotification());
```

For sending the notification to an arbitrary mobile number, use below syntax:
```php
<?php
use Illuminate\Support\Facades\Notification

Notification::route('helliomessaging', '233242813656')
    ->notify(new App\Notifications\HellioMessagingTestNotification());
```

## Validator
You can validate sent OTPs using provided validation rule named `helliomessaging_otp` as shown below:
```php
<?php

use Illuminate\Support\Facades\Validator

$data = ['mobile_number' => '233242813656', 'token' => '1234'];

$validator = Validator::make($data, [
    'mobile_number' => ['required', 'digits:10'],
    'token' => ['required', 'digits:4', 'helliomessaging_otp'], // default key for source number is 'mobile_number', you can customize this using 'helliomessaging_otp:key_name'
]);

if ($validator->fails()) {
    // report errors
}
```

### License

See [LICENSE](LICENSE) file.
