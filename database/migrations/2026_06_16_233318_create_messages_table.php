<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)
                ->comment('Recipient phone number');

            $table->text('content')
                ->comment('Message content to be sent');

            $table->boolean('is_sent')
                ->default(false)
                ->comment('Indicates whether the message has been sent');

            $table->uuid('external_message_id')
                ->nullable()
                ->comment('Message ID returned by the external messaging provider');

            $table->timestamp('sent_at')
                ->nullable()
                ->comment('Date and time when the message was sent');

            $table->timestamps();

            $table->index('is_sent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
