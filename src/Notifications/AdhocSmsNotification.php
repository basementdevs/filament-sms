<?php

declare(strict_types=1);

namespace Basement\Sms\Notifications;

use Basement\Sms\Notifications\Messages\TwilioMessage;
use Illuminate\Notifications\Notification;

final class AdhocSmsNotification extends Notification
{
    protected string $phone;

    protected string $content;

    public function __construct(string $phone, string $content)
    {
        $this->phone = $phone;
        $this->content = $content;
    }

    public function via(object $notifiable): array
    {
        return ['sms'];
    }

    public function toSms(object $notifiable): TwilioMessage
    {
        return TwilioMessage::make($notifiable)->to($this->phone)->content($this->content);
    }
}
