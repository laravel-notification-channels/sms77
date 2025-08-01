# Seven.io (formerly SMS77) notifications channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/sms77.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/sms77)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/github/actions/workflow/status/laravel-notification-channels/sms77/php.yml?style=flat-square)](https://github.com/laravel-notification-channels/sms77/actions) 
[![StyleCI](https://github.styleci.io/repos/259466891/shield?branch=master)](https://github.styleci.io/repos/259466891)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/sms77.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/sms77)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/sms77.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/sms77)

This package makes it easy to send notifications using [Seven.io (formerly SMS77)](https://www.seven.io) with Laravel.

## Contents

- [Installation](#installation)
	- [Setting up the Seven.io service](#setting-up-the-SMS77-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

This package can be installed via composer:

```composer require laravel-notification-channels/sms77```

### Setting up the SMS77 service

1. Create an account and get the API key [here](https://www.seven.io)

2. Add the API key to the `services.php` config file:

	```php
	// config/services.php
	...
	'sms77' => [
		'api_key' => env('SEVEN_API_KEY')
	],
	...
	```

## Usage

You can use this channel by adding `SMS77Channel::class` to the array in the `via()` method of your notification class. You need to add the `toSms77()` method which should return a `new SMS77Message()` object.

```php
<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\SMS77\SMS77Channel;
use NotificationChannels\SMS77\SMS77Message;

class InvoicePaid extends Notification
{
    public function via($notifiable)
    {
        return [SMS77Channel::class];
    }

    public function toSms77() {
        return (new SMS77Message('Hallo!'))
        ->from('Max');
    }
}
```

### Available Message methods

- `getPayloadValue($key)`: Returns payload value for a given key.
- `content(string $message)`: Sets SMS message text.
- `to(string $number)`: Set recipients number. 
- `from(string $from)`: Set senders name.
- `delay(string $timestamp)`: Delays message to given timestamp.
- `flash()`: Sends SMS as flash message.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email mail@mxschll.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Maximilian Schöll](https://github.com/mxschll)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
