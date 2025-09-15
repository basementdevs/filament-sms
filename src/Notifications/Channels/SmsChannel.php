<?php

declare(strict_types=1);

namespace Basement\Sms\Notifications\Channels;

use Basement\Sms\Enums\SmsEventType;
use Basement\Sms\Models\SmsMessage;
use Basement\Sms\Notifications\Messages\TwilioMessage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Client;

final class SmsChannel
{
    public function send(Model $notifiable, Notification $notification): void
    {
        [$recipientId, $recipientType] = [$notifiable->getKey(), $notifiable->getMorphClass()];

        $message = $notification->toSms($notifiable);

        $recipientNumber = $message->getRecipientPhoneNumber() ?? $notifiable->routeNotificationFor('sms', $notification);

        $smsMessage = new SmsMessage([
            'notification_class' => $notification::class,
            'content' => $message->toArray(),
            'recipient_id' => $recipientId,
            'recipient_type' => $recipientType,
            'recipient_number' => is_array($recipientNumber) ? null : $recipientNumber,
        ]);

        if (isset($notification->related) && $notification->related instanceof Model) {
            $smsMessage->related_type = $notification->related->getMorphClass();
            $smsMessage->related_id = $notification->related->getKey();
        }

        $smsMessage->save();

        if (blank($recipientNumber)) {
            $smsMessage->events()->create([
                'type' => SmsEventType::Undelivered,
                'payload' => [
                    'error' => 'No recipient number provided.',
                ],
                'occurred_at' => now(),
            ]);

            return;
        }

        $result = $this->sendViaTwilio($message, $recipientNumber);

        $smsMessage->update([
            'external_reference_id' => $result->sid,
        ]);

        $smsMessage->events()->create([
            'type' => SmsEventType::Dispatched,
            'payload' => $result->toArray(),
            'occurred_at' => now(),
        ]);
    }

    private function sendViaTwilio(TwilioMessage $message, mixed $recipientNumber): MessageInstance
    {
        $client = app(Client::class);

        $messageContent = [
            'from' => $message->getFrom(),
            'body' => $message->getContent(),
        ];

        if (! app()->environment('local')) {
            // TODO: Add status callback
            $messageContent['statusCallback'] = config('app.url').'/webhooks/twilio/status';
        }

        return $client->messages->create(
            config('local_development.twilio.to_number') ?: $recipientNumber,
            $messageContent,
        );
    }
}
