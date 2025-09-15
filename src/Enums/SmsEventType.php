<?php

declare(strict_types=1);

namespace Basement\Sms\Enums;

use Filament\Support\Contracts\HasLabel;

enum SmsEventType: string implements HasLabel
{
    // Internal
    case Dispatched = 'dispatched';
    case FailedDispatch = 'failed_dispatch';
    case RateLimited = 'rate_limited';
    case BlockedByDemoMode = 'blocked_by_demo_mode';

    // External
    case Queued = 'queued';
    case Canceled = 'canceled';
    case Sent = 'sent';
    case Failed = 'failed';
    case Delivered = 'delivered';
    case Undelivered = 'undelivered';
    case Read = 'read';

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
