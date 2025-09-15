<?php

declare(strict_types=1);

namespace Basement\Sms\Filament\Pages;

use Basement\Sms\Filament\SmsMessageResource;
use Filament\Resources\Pages\ListRecords;

final class ListSmsMessages extends ListRecords
{
    protected static string $resource = SmsMessageResource::class;
}
