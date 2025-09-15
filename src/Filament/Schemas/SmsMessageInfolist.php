<?php

declare(strict_types=1);

namespace Basement\Sms\Filament\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class SmsMessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('notification_class'),
                TextEntry::make('external_reference_id'),
                TextEntry::make('related_id'),
                TextEntry::make('related_type'),
                TextEntry::make('recipient_id'),
                TextEntry::make('recipient_type'),
                TextEntry::make('recipient_number'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
