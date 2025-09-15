<?php

declare(strict_types=1);

namespace Basement\Sms\Filament;

use BackedEnum;
use Basement\Sms\Filament\Pages\ListSmsMessages;
use Basement\Sms\Filament\Pages\ViewSmsMessage;
use Basement\Sms\Filament\Schemas\SmsMessageForm;
use Basement\Sms\Filament\Schemas\SmsMessageInfolist;
use Basement\Sms\Filament\Tables\SmsMessagesTable;
use Basement\Sms\Models\SmsMessage;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

final class SmsMessageResource extends Resource
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationGroup(): UnitEnum|string|null
    {
        return config('filament-sms.navigation_group', 'Logs');
    }

    public static function getNavigationLabel(): string
    {
        return config('filament-sms.navigation_label', 'Webhooks');
    }

    public static function getLabel(): string
    {
        return config('filament-sms.navigation_label', 'Webhooks');
    }

    public static function getNavigationSort(): ?int
    {
        return config('filament-sms.navigation_sort', 10);
    }

    public static function getModel(): string
    {
        return config('filament-sms.model', SmsMessage::class);
    }

    public static function getNavigationIcon(): BackedEnum
    {
        return config('filament-sms.navigation_icon_inactive', Heroicon::DocumentText);
    }

    public static function getActiveNavigationIcon(): BackedEnum
    {
        return config('filament-sms.navigation_icon_active', Heroicon::DocumentText);
    }

    public static function form(Schema $schema): Schema
    {
        return SmsMessageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SmsMessageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SmsMessagesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSmsMessages::route('/'),
            'view' => ViewSmsMessage::route('/{record}'),
        ];
    }
}
