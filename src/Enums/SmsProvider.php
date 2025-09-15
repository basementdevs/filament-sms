<?php

declare(strict_types=1);

namespace Basement\Sms\Enums;

use Filament\Support\Contracts\HasLabel;

enum SmsProvider: string implements HasLabel
{
    case Twilio = 'twilio';

    public function getLabel(): string
    {
        return $this->name;
    }
}
