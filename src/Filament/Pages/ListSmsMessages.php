<?php

declare(strict_types=1);

namespace Basement\Sms\Filament\Pages;

use Basement\Sms\Filament\SmsMessageResource;
use Basement\Sms\Filament\Widgets\SmsRecipientStats;
use Basement\Sms\Models\SmsMessage;
use Basement\Sms\Notifications\AdhocSmsNotification;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Colors\Color;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

final class ListSmsMessages extends ListRecords
{
    protected static string $resource = SmsMessageResource::class;

    public function getHeaderWidgets(): array
    {
        return [
            SmsRecipientStats::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            Tab::make('All')
                ->label('All')
                ->badge(SmsMessage::count())
                ->badgeColor('primary')
                ->query(fn () => SmsMessage::query()),
        ];

        $recipientTypes = SmsMessage::select('recipient_type')
            ->distinct()
            ->pluck('recipient_type');

        foreach ($recipientTypes as $type) {
            $tabs[] = Tab::make(class_basename($type))
                ->label(class_basename($type))
                ->badge(SmsMessage::where('recipient_type', $type)->count())
                ->badgeColor($this->getModelColor($type))
                ->query(fn () => SmsMessage::where('recipient_type', $type));
        }

        return $tabs;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('send-adhoc-sms')
                ->label('Send SMS')
                ->action(function (array $data) {
                    $phone = mb_trim($data['phone']);
                    $message = $data['message'];
                    $content = mb_substr($message, 0, 160);
                    auth()->user()->notify(new AdhocSmsNotification($phone, $content));
                })
                ->schema([
                    PhoneInput::make('phone')
                        ->label('Phone (E.164)')
                        ->strictMode(true)
                        ->required(),
                    Textarea::make('message')
                        ->label('Message')
                        ->required(),
                ]),
        ];

    }

    protected function getModelColor(string $state): array
    {
        $color = '#'.mb_substr(md5($state), 0, 6);

        return Color::hex($color);
    }
}
