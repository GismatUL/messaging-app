<?php

namespace App\Repositories;

use App\Models\Message;
use App\Repositories\Contracts\MessageRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MessageRepository implements MessageRepositoryInterface
{
    public function getPendingMessages(int $limit = 2): Collection
    {
        return Message::query()
            ->where('is_sent', false)
            ->oldest()
            ->limit($limit)
            ->get();
    }

    public function markAsSent(Message $message, string $externalMessageId): Message
    {
        $message->update([
            'is_sent' => true,
            'external_message_id' => $externalMessageId,
            'sent_at' => now(),
        ]);

        return $message->refresh();
    }

    public function getSentMessages(int $perPage = 15): LengthAwarePaginator
    {
        return Message::query()
            ->sent()
            ->latest('sent_at')
            ->paginate($perPage);
    }
}