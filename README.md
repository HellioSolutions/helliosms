# Hellio Messaging CaaS API Integration For Laravel

### About

Official Laravel package that integrates with [Hellio Messaging](https://helliomessaging.com)'s fleets of APIs nicely
with [Laravel](https://laravel.com/) 5+ adding support for **SMS**, **Notification**, **Voice SMS**, **OTP Codes**, **OTP Validation**, **Email Validator Service** & **Laravel Validator** as well.

### Registration

[Sign up](https://app.helliomessaging.com/try-hellio) for Hellio Messaging and get the auth key from your account. You
can find the `client_id` and `application_secret` from `Profile Settings > API Keys & Webhooks` key after signing in.

### Installation

```bash
composer require helliosolutions/helliosms
```

#### Laravel < 5.5

Once the package is installed, open your `app/config/app.php` configuration file and locate the `providers` key. Add the
following line to the end:

```php
\Hellio\HellioMessaging\HellioMessagingServiceProvider::class
```

Next, locate the `aliases` key and add the following line:

```php
'HellioMessaging' => \Hellio\HellioMessaging\Facades\HellioMessaging::class,
```

### Configuration

Put the credentials and preferences in ENV with the keys:

`HELLIO_MESSAGING_CLIENT_ID`\
`HELLIO_MESSAGING_APPLICATION_SECRET`\
`HELLIO_MESSAGING_DEFAULT_SENDER`\
`HELLIO_MESSAGING_API_VERSION`

If you want to customize this, publish the default configuration which will create a config
file `config/helliomessaging.php`.

```php

### Configuration Structure
The configuration looks like this:
<?php

return [

    /**
     * Live API url
     *
     */
    'baseUrl' => 'https://api.helliomessaging.com/',

    /**
     * Client Id
     *
     */
    'clientId' => getenv('HELLIO_MESSAGING_CLIENT_ID'),

    /**
     * Application Secret
     *
     */
    'applicationSecret' => getenv('HELLIO_MESSAGING_APPLICATION_SECRET'),


    /**
     * Default Sender Id
     *
     */
    'defaultSender' => getenv('HELLIO_MESSAGING_DEFAULT_SENDER'),


    /**
     * Default API version
     *
     */

    'apiVersion' => getenv('HELLIO_MESSAGING_API_VERSION'),

    /**
     * Default username
     *
     */
    'username' => getenv('HELLIO_MESSAGING_USERNAME'),


    /**
     * Default password
     *
     */

    'password' => getenv('HELLIO_MESSAGING_PASSWORD'),

];


```
## Publish Service provider:
```bash

$ php artisan vendor:publish --provider="Hellio\HellioMessaging\HellioMessagingServiceProvider" --tag=helliomessaging

```

## Usage:

Open your .env file and add your api key like so:

```bash

HELLIO_MESSAGING_CLIENT_ID=xxxxxxxx
HELLIO_MESSAGING_APPLICATION_SECRET=xxxxxxxx
HELLIO_MESSAGING_DEFAULT_SENDER=YourSenderName //Max of 11 characters
HELLIO_MESSAGING_API_VERSION= // From v1 to v3. Kindly check the documentation for the appropriate version you wish to use

```

### Basic

- Send an SMS to one or more mobile numbers.

```php
<?php

//The first call assumes that you've already set a default sender_id in the helliomessaging.php config file.
$response = HellioMessaging::sms('233242813656', 'Hello there!');

$response = HellioMessaging::sms('233242813656', 'Hello there!', 'HellioSMS');

$response = HellioMessaging::sms(null, [
    ['mobile_number' => ['233242813656', '233591451609'], 'message' => 'Hello there!'],
    ['mobile_number' => ['233203555816'], 'message' => 'Come here!'],
], 'HellioSMS');
```

## SMS Responses:

### On Success

```json
{
    "success": true,
    "message": "1 sms sent successfully"
}
```

- Send OTP to a mobile number.

```php
<?php

$response = HellioMessaging::otp('233242813656');

$response = HellioMessaging::otp('233242813656', 'HellioSMS', '4', '10');

$response = HellioMessaging::otp('233242813656', 'HellioSMS', '4', '10', '##OTP## is your OTP, Please dont share it with anyone.');
```

- Verify OTP sent to a mobile number.

```php
<?php

$response = HellioMessaging::verify('233242813656', 1290); // returns true or false
```

## OTP Responses:

### On Success

```json
{
    "success": true,
    "data": {
        "status": true,
        "token": "528830",
        "message": "OTP generated"
    },
    "message": "2FA code sent successfully"
}
```

### Does not exist

```json
{
    "success": true,
    "data": {
        "status": false,
        "message": "OTP does not exist"
    },
}
```

### Not Valid*

```json
{
    "success": true,
    "data": {
        "status": false,
        "message": "OTP is not valid"
    },
}
```

### Expired

```json
{
    "success": true,
    "data": {
        "status": false,
        "message": "OTP Expired"
    },
}
```

## Email Validation

- Validate email addresses to check if they're correct and can recieve emails.

```php
<?php

$response = HellioMessaging::emailvalidator('someemail@domain.com', 'Marketing leads');

$response = HellioMessaging::emailvalidator(['someemail@domain.com', 'support@domain.com'], 'Marketing leads'); // Validate multiple emails at once
```

## Hellio Account Balance

- Check your Hellio Messaging account balance with ease.

```php
<?php

$response = HellioMessaging::balance();
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

use Hellio\HellioMessaging\Message\HellioMessagingSms;

public function toHellioMessaging()
{
    return (new HellioMessagingSms)
        ->message(__('This is just a test message.'))
	    ->sender_id(__('HellioSMS')) // [Optional] - Will pick default sender ID from HELLIO_MESSAGING_DEFAULT_SENDER or if not set, will use the application name.
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

## OTP Validator

You can validate sent OTPs using provided validation rule named `hellio_otp` as shown below:

```php
<?php

use Illuminate\Support\Facades\Validator

$data = ['mobile_number' => '233242813656', 'token' => '1234'];

$validator = Validator::make($data, [
    'mobile_number' => ['required', 'digits:10'],
    'token' => ['required', 'digits:4', 'hellio_otp'], // default key for source number is 'mobile_number', you can customize this using 'hellio_otp:key_name'
]);

if ($validator->fails()) {
    // report errors
}
```

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email support@helliomessaging.com instead of using the issue
tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Albert Ninyeh](https://github.com/VimKanzoGH)
- [Norris Oduro Tei](https://github.com/Norris1z)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
