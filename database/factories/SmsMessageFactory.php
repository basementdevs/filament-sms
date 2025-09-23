<?php

declare(strict_types=1);

namespace Basement\Sms\Database\Factories;

use Basement\Sms\Models\SmsMessage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

final class SmsMessageFactory extends Factory
{
    protected $model = SmsMessage::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'notification_class' => self::class,
            'external_reference_id' => $this->faker->unique()->uuid(),
            'content' => json_encode([
                'recipient_phone_number' => $this->faker->phoneNumber(),
                'content' => $this->faker->sentence(),
                'from_phone_number' => $this->faker->phoneNumber(),
            ]),
            'recipient_number' => $this->faker->phoneNumber(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function withRecipient(Model $model): self
    {
        return $this->state(fn () => [
            'recipient_id' => $model->getKey(),
            'recipient_type' => $model::class,
        ]);
    }

    public function withRelated(Model $model): self
    {
        return $this->state(fn () => [
            'related_id' => $model->getKey(),
            'related_type' => $model::class,
        ]);
    }
}
