<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\MessageService;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Services\SmsGatewayService;
use App\Models\Message;
use Illuminate\Support\Facades\Cache;

class MessageServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_message_and_marks_it_as_sent(): void
    {
        Cache::shouldReceive('store')
            ->once()
            ->with('redis')
            ->andReturnSelf();

        Cache::shouldReceive('put')
            ->once();

        $message = Message::factory()->create([
            'is_sent' => false,
        ]);

        $gateway = $this->mock(SmsGatewayService::class);
        $gateway->shouldReceive('send')
            ->once()
            ->with($message->phone, $message->content)
            ->andReturn([
                'message' => 'Accepted',
                'messageId' => 'test-message-id',
            ]);

        $repository = app(MessageRepositoryInterface::class);

        $service = new MessageService($repository, $gateway);

        $sentMessage = $service->send($message);

        $this->assertTrue($sentMessage->is_sent);
        $this->assertEquals('test-message-id', $sentMessage->external_message_id);
        $this->assertNotNull($sentMessage->sent_at);
    }
}
