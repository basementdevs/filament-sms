# Basement Developers – Filament SMS

[![Latest Version on Packagist](https://img.shields.io/packagist/v/basementdevs/filament-sms.svg?style=flat-square)](https://packagist.org/packages/basementdevs/filament-sms)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/basementdevs/filament-sms/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/basementdevs/filament-sms/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/basementdevs/filament-sms/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/basementdevs/filament-sms/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/basementdevs/filament-sms.svg?style=flat-square)](https://packagist.org/packages/basementdevs/filament-sms)

A Laravel package that provides an SMS Notification channel powered by Twilio and a Filament v4 resource to view SMS logs in your admin panel. It ships with:
- A custom Notification channel "sms" that dispatches messages via Twilio and persists logs and delivery events.
- A Filament Plugin that registers an "SMS" resource for browsing messages and events.
- Publishable config, views, and auto-discovered database migrations.

Note: This README documents what is present in the repository. Where behavior depends on the host application and isn't explicit in the codebase, TODOs are marked for follow-up.

## Overview of the stack
- Language: PHP 8.3
- Framework: Laravel (Illuminate 12.x APIs)
- Admin: Filament v4
- Package manager: Composer
- Testing: Pest + Orchestra Testbench

## Requirements
- PHP ^8.3
- Laravel 12.x compatible application
- Filament ^4.0 installed in the host app
- Composer
- Twilio SDK available in the host app
  - The package references Twilio\Rest\Client. In this repository the dependency is in require-dev for testing. In your application, ensure `twilio/sdk` is installed.

## Installation
Install via Composer in your Laravel application:

```bash
composer require basementdevs/filament-sms
```

If your app does not already depend on Twilio's SDK:

```bash
composer require twilio/sdk
```

The service provider is auto-discovered via Laravel's package discovery; no manual registration needed.

### Publish assets and run migrations
This package discovers migrations automatically. If you prefer to publish them, use:

```bash
php artisan vendor:publish --tag="filament-sms-migrations"
php artisan migrate
```

Publish the config file:

```bash
php artisan vendor:publish --tag="filament-sms-config"
```

Optionally publish the views:

```bash
php artisan vendor:publish --tag="filament-sms-views"
```

## Configuration
The published config file is `config/filament-sms.php` and controls:
- Filament navigation group/label/icons/sort
- Backing model for SMS messages
- Enum of providers (currently only Twilio)

Example (defaults from this repo):

```php
return [
    'navigation_group' => 'Logs',
    'navigation_label' => 'SMS',
    'navigation_icon_inactive' => \Filament\Support\Icons\Heroicon::OutlinedChatBubbleBottomCenterText,
    'navigation_icon_active' => \Filament\Support\Icons\Heroicon::ChatBubbleBottomCenterText,
    'navigation_sort' => 100,

    'model' => \Basement\Sms\Models\SmsMessage::class,
    'providers_enum' => \Basement\Sms\Enums\SmsProvider::class,
];
```

### Environment variables
Twilio credentials and defaults are required by your application. The following are commonly used; verify in your project:

```env
TWILIO_ACCOUNT_SID=
TWILIO_AUTH_TOKEN=
TWILIO_NUMBER=
```

## Filament integration
Register the plugin on your Filament Panel to expose the SMS resource in the navigation:

```php
use Basement\Sms\FilamentSmsPlugin;

$panel->plugins([
    FilamentSmsPlugin::make(),
]);
```

The resource lists sms_messages with details like recipient, related models, timestamps, and recorded events.

## Using the SMS notification channel
This package hooks into Laravel's Notification ChannelManager and registers a channel key `sms`.

1) Add a `routeNotificationForSms` (or `routeNotificationFor('sms')`) on your Notifiable model to provide a phone number when not specified by the message:

```php
public function routeNotificationForSms($notification): ?string
{
    return $this->phone_number; // E.164 recommended
}
```

2) Create a Notification that defines `toSms()` and returns a TwilioMessage:

```php
use Basement\Sms\Notifications\Messages\TwilioMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via(object $notifiable): array
    {
        return ['sms'];
    }

    public function toSms(object $notifiable): TwilioMessage
    {
        return TwilioMessage::make($notifiable)
            ->to(null) // optional, if your notifiable supplies the route
            ->from(config('twilio-notification-channel.from')) // or hardcode a valid Twilio number
            ->content('Your account was approved!');
    }
}
```

3) Dispatch the notification as usual:

```php
$user->notify(new AccountApproved());
```

The channel will store an `sms_messages` record and related delivery events. In local environments the recipient can be overridden by the `local_development.twilio.to_number` config value.

Note: Delivery status callbacks are not wired up by default. See TODOs above.

## Scripts (Composer)
Available scripts in this repository:
- analyse: `composer analyse` – runs PHPStan
- test: `composer test` – runs Pest tests
- test-coverage: `composer test-coverage` – runs tests with coverage
- format: `composer format` – runs Pint


## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities
Please review [our security policy](../../security/policy) on how to report security vulnerabilities.
