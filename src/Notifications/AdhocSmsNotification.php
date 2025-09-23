<?php

declare(strict_types=1);

namespace Basement\Sms\Notifications;

use Basement\Sms\Notifications\Messages\TwilioMessage;
use Illuminate\Notifications\Notification;

final class AdhocSmsNotification extends Notification
{
    protected string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function via(object $notifiable): array
    {
        return ['sms'];
    }

    public function toSms(object $notifiable): TwilioMessage
    {
        return TwilioMessage::make($notifiable)->content($this->content);
    }
}
