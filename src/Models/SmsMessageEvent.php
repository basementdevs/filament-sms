<?php

declare(strict_types=1);

namespace Basement\Sms\Models;

use Basement\Sms\Database\Factories\SmsMessageEventFactory;
use Basement\Sms\Enums\SmsEventType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class SmsMessageEvent extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'sms_message_events';

    protected $fillable = [
        'type',
        'payload',
        'occurred_at',
    ];

    protected $casts = [
        'type' => SmsEventType::class,
        'payload' => 'array',
        'occurred_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<SmsMessage, $this>
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(SmsMessage::class);
    }

    protected static function newFactory(): SmsMessageEventFactory
    {
        return SmsMessageEventFactory::new();
    }
}
