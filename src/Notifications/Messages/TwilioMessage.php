<?php

declare(strict_types=1);

namespace Basement\Sms\Notifications\Messages;

final class TwilioMessage
{
    public function __construct(
        private ?string $recipientPhoneNumber = null,
        private ?string $content = null,
        private ?string $from = null,
    ) {
        $this->from = config('filament-sms.twilio.from');
    }

    public static function make(object $notifiable): static
    {
        return app()->makeWith(self::class, [
            'notifiable' => $notifiable,
        ]);
    }

    public function to(?string $recipientPhoneNumber): self
    {
        $this->recipientPhoneNumber = $recipientPhoneNumber;

        return $this;
    }

    public function getRecipientPhoneNumber(): ?string
    {
        return $this->recipientPhoneNumber;
    }

    public function content(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function from(string $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function toArray(): array
    {
        return [
            'recipient_phone_number' => $this->recipientPhoneNumber,
            'content' => $this->content,
            'from' => $this->from,
        ];
    }
}
