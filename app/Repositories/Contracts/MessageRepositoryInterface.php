<?php

namespace App\Repositories\Contracts;

use App\Models\Message;
use Illuminate\Support\Collection;

interface MessageRepositoryInterface
{
    public function getPendingMessages(int $limit = 2): Collection;

    public function markAsSent(Message $message, string $externalMessageId): Message;
}