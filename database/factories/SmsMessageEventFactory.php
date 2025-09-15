<?php

declare(strict_types=1);

namespace Basement\Sms\Database\Factories;

use Basement\Sms\Enums\SmsEventType;
use Basement\Sms\Models\SmsMessageEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

final class SmsMessageEventFactory extends Factory
{
    protected $model = SmsMessageEvent::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(SmsEventType::cases()),
            'payload' => [],
            'occurred_at' => now(),
        ];
    }
}
