<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppMessageService
{
    public function send(?string $phoneNumber, string $message, array $meta = []): bool
    {
        if (!$phoneNumber) {
            return false;
        }

        $webhookUrl = config('services.whatsapp.webhook_url');
        $payload = [
            'phone' => $phoneNumber,
            'message' => $message,
            'meta' => $meta,
        ];

        if (!$webhookUrl) {
            Log::info('WhatsApp delivery skipped because no webhook is configured.', $payload);

            return true;
        }

        $response = Http::timeout(10)
            ->acceptJson()
            ->post($webhookUrl, array_filter([
                'phone' => $phoneNumber,
                'message' => $message,
                'secret' => config('services.whatsapp.secret'),
                'meta' => $meta,
            ], static fn ($value) => $value !== null));

        if ($response->failed()) {
            Log::warning('WhatsApp delivery failed.', [
                'status' => $response->status(),
                'body' => $response->body(),
                'phone' => $phoneNumber,
            ]);

            return false;
        }

        return true;
    }
}
