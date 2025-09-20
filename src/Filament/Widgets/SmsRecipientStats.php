<?php

namespace Basement\Sms\Filament\Widgets;

use Basement\Sms\Models\SmsMessage;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
class SmsRecipientStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return $this->getSmsRecipientStats();
    }

    private function getSmsRecipientStats(): array
    {
        $totalSmsMessages = SmsMessage::count();
        $stats = [];

        $recipientTypes = SmsMessage::select('recipient_type')
            ->distinct()
            ->pluck('recipient_type');

        foreach ($recipientTypes as $type) {
            $count = SmsMessage::where('recipient_type', $type)->count();
            $percentage = $totalSmsMessages > 0 ? round(($count / $totalSmsMessages) * 100, 2) : 0;
            $stats[] = Stat::make(class_basename($type),"{$percentage}%")
                ->color($this->getModelColor($type))
                ->description("{$count} of {$totalSmsMessages} messages");
        }
        return $stats;
    }

    protected function getModelColor(string $state): array
    {
        $color = '#'.mb_substr(md5($state), 0, 6);

        return Color::hex($color);
    }
}
