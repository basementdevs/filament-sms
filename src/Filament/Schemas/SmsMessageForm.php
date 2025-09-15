<?php

declare(strict_types=1);

namespace Basement\Sms\Filament\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class SmsMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('notification_class')
                    ->required(),
                TextInput::make('external_reference_id'),
                TextInput::make('content')
                    ->required(),
                TextInput::make('related_id'),
                TextInput::make('related_type'),
                TextInput::make('recipient_id'),
                TextInput::make('recipient_type'),
                TextInput::make('recipient_number'),
            ]);
    }
}
