<?php

declare(strict_types=1);

namespace Basement\Sms\Models;

use Basement\Sms\Database\Factories\SmsMessageFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class SmsMessage extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'sms_messages';

    protected $fillable = [
        'notification_class',
        'external_reference_id',
        'content',
        'recipient_id',
        'recipient_type',
        'recipient_number',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    /**
     * @return MorphTo<Model, $this>
     */
    public function related(): MorphTo
    {
        return $this->morphTo(
            name: 'related',
            type: 'related_type',
            id: 'related_id',
        );
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function recipient(): MorphTo
    {
        return $this->morphTo(
            name: 'recipient',
            type: 'recipient_type',
            id: 'recipient_id',
        );
    }

    /**
     * @return HasMany<SmsMessageEvent, $this>
     */
    public function events(): HasMany
    {
        return $this->hasMany(SmsMessageEvent::class, 'sms_message_id');
    }

    protected static function newFactory(): SmsMessageFactory
    {
        return SmsMessageFactory::new();
    }
}
