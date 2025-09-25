<?php

declare(strict_types=1);

namespace Basement\Sms\Filament\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class SmsMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->limit(3)
                    ->tooltip(fn ($record): string => $record->id)
                    ->label('ID'),
                TextColumn::make('recipient.name')
                    ->searchable(),
                TextColumn::make('recipient_type')
                    ->badge()
                    ->fullMorph()
                    ->searchable(),
                TextColumn::make('recipient_number')
                    ->searchable(),
                TextColumn::make('related')
                    ->formatStateUsing(fn ($state): string => $state?->name ?? 'N/A')
                    ->searchable(),
                TextColumn::make('related_type')
                    ->fullMorph()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
