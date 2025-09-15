<?php

declare(strict_types=1);

namespace Basement\Sms;

use Basement\Sms\Models\SmsMessage;
use Basement\Sms\Models\SmsMessageEvent;
use Basement\Sms\Notifications\ChannelManager;
use Basement\Sms\Notifications\Channels\SmsChannel;
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
