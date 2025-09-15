<?php

declare(strict_types=1);

// config for Basement/Sms
use Basement\Sms\Enums\SmsProvider;
use Basement\Sms\Models\SmsMessage;
use Filament\Support\Icons\Heroicon;

return [
    'navigation_group' => 'Logs',
    'navigation_label' => 'SMS',
    'navigation_icon_inactive' => Heroicon::OutlinedChatBubbleBottomCenterText,
    'navigation_icon_active' => Heroicon::ChatBubbleBottomCenterText,
    'navigation_sort' => 100,

    'model' => SmsMessage::class,
    'providers_enum' => SmsProvider::class,
];
