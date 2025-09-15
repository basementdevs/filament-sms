<?php

declare(strict_types=1);

namespace Basement\Sms\Filament\Pages;

use Basement\Sms\Filament\SmsMessageResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateSmsMessage extends CreateRecord
{
    protected static string $resource = SmsMessageResource::class;

    // TODO: make a base notification class to send dummy sms messages
}
