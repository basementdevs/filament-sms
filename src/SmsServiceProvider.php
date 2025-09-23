<?php

declare(strict_types=1);

namespace Basement\Sms;

use Basement\Sms\Models\SmsMessage;
use Basement\Sms\Models\SmsMessageEvent;
use Basement\Sms\Notifications\ChannelManager;
use Basement\Sms\Notifications\Channels\SmsChannel;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Notifications\ChannelManager as BaseChannelManager;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class SmsServiceProvider extends PackageServiceProvider
{
    public function register()
    {
        parent::register();

        $this->app->singleton(BaseChannelManager::class, fn (Container $app) => (new ChannelManager($app))
            ->extend('sms', fn (): SmsChannel => $this->app->make(SmsChannel::class)));

        TextColumn::macro('morphColor', fn () => $this->color(function ($state): array {
            $color = '#'.mb_substr(md5($state), 0, 6);

            return Color::hex($color);
        }));

        TextColumn::macro('fullMorph', fn () => $this->morphColor()
            ->formatStateUsing(fn ($state): string => ucfirst(class_basename((string) $state)))
            ->badge());
    }

    public function boot(): void
    {
        parent::boot();

        Relation::morphMap([
            'sms_message' => SmsMessage::class,
            'sms_message_event' => SmsMessageEvent::class,
        ]);
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-sms')
            ->hasConfigFile()
            ->hasViews()
            ->discoversMigrations();
    }
}
