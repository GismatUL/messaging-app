<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Jobs\SendMessageJob;
use App\Models\Message;
use Illuminate\Support\Facades\Queue;

class DispatchMessagesCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_dispatches_only_pending_messages_to_queue(): void
    {
        Queue::fake();

        Message::factory()->count(3)->create([
            'is_sent' => false,
        ]);

        Message::factory()->create([
            'is_sent' => true,
            'external_message_id' => fake()->uuid(),
            'sent_at' => now(),
        ]);

        $this->artisan('messages:dispatch')
            ->expectsOutput('Dispatched 2 message(s).')
            ->assertSuccessful();

        Queue::assertPushed(SendMessageJob::class, 2);
    }
}
