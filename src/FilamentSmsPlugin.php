<?php

declare(strict_types=1);

namespace Basement\Sms;

use Basement\Sms\Filament\SmsMessageResource;
use Filament\Contracts\Plugin;
use Filament\Panel;

final class FilamentSmsPlugin implements Plugin
{
    public static function make(): self
    {
        return app(self::class);
    }

    public function getId(): string
    {
        return 'filament-sms';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            SmsMessageResource::class,
        ]);
    }

    public function boot(Panel $panel): void {}
}
