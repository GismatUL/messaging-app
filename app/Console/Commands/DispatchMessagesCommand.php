<?php

namespace App\Console\Commands;

use App\Jobs\SendMessageJob;
use App\Repositories\Contracts\MessageRepositoryInterface;
use Illuminate\Console\Command;

class DispatchMessagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'messages:dispatch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch pending messages to queue';

    /**
     * Execute the console command.
     */
    public function handle(MessageRepositoryInterface $messageRepository) : int
    {
        $messages = $messageRepository->getPendingMessages(
            config('messages.batch_size')
        );

        foreach ($messages as $message) {
            SendMessageJob::dispatch($message);
        }

        $this->info(
            "Dispatched {$messages->count()} message(s)."
        );

        return self::SUCCESS;
    }
}
