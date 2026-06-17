<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Message;

class MessageApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_sent_messages(): void
    {
        Message::factory()->create([
            'is_sent' => true,
            'external_message_id' => fake()->uuid(),
            'sent_at' => now(),
        ]);

        Message::factory()->create([
            'is_sent' => false,
            'external_message_id' => null,
            'sent_at' => null,
        ]);

        $response = $this->getJson('/api/v1/messages/sent');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'phone',
                        'content',
                        'message_id',
                        'sent_at',
                    ],
                ],
            ]);
    }
}
