<?php

namespace App\Services;

use App\Models\Message;
use App\Repositories\Contracts\MessageRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;
use RuntimeException;

class MessageService
{
    public function __construct(
        private readonly MessageRepositoryInterface $messageRepository,
        private readonly SmsGatewayService $smsGatewayService
    ) {
    }

    public function send(Message $message): Message
    {
        $this->validateMessageContent($message->content);

        $response = $this->smsGatewayService->send(
            $message->phone,
            $message->content
        );

        if (! isset($response['messageId'])) {
            throw new RuntimeException('Message provider response does not contain messageId.');
        }

        $sentMessage = $this->messageRepository->markAsSent(
            $message,
            $response['messageId']
        );

        $this->cacheSentMessage($sentMessage);

        return $sentMessage;
    }

    private function validateMessageContent(string $content): void
    {
        $maxLength = config('messages.content_max_length', 160);

        if (mb_strlen($content) > $maxLength) {
            throw new InvalidArgumentException("Message content cannot exceed {$maxLength} characters.");
        }
    }

    private function cacheSentMessage(Message $message): void
    {
        Cache::store('redis')->put(
            "sent_message:{$message->id}",
            [
                'message_id' => $message->external_message_id,
                'sent_at' => $message->sent_at?->toISOString(),
            ],
            now()->addDays(7)
        );
    }
}