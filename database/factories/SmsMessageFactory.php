<?php

declare(strict_types=1);

namespace Basement\Sms\Database\Factories;

use Basement\Sms\Models\SmsMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

final class SmsMessageFactory extends Factory
{
    protected $model = SmsMessage::class;

    public function definition(): array
    {
        return [
            'content' => json_encode([
                'recipient_phone_number' => '+1234567890',
                'content' => 'test',
                'from_phone_number' => '+0987654321',
            ]),
            'notification_class' => self::class,
            'quota_usage' => 0,
        ];
    }
}
