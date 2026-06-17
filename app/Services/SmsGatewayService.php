<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SmsGatewayService
{
    public function send(string $phone, string $content): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-ins-auth-key' => config('services.insider.auth_key'),
        ])->post(config('services.insider.webhook_url'), [
            'to' => $phone,
            'content' => $content,
        ]);

        $response->throw();

         return $response->json() ?? [
            'message' => 'Accepted',
            'messageId' => (string) Str::uuid(),
        ];
    }
}