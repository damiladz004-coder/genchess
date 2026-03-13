<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaystackService
{
    public function initialize(string $email, int $amountKobo, string $reference, string $callbackUrl, array $metadata = []): array
    {
        $response = Http::withToken($this->secretKey())
            ->acceptJson()
            ->post($this->baseUrl() . '/transaction/initialize', [
                'email' => $email,
                'amount' => $amountKobo,
                'reference' => $reference,
                'currency' => 'NGN',
                'callback_url' => $callbackUrl,
                'metadata' => $metadata,
            ]);

        return $response->json();
    }

    public function verify(string $reference): array
    {
        $response = Http::withToken($this->secretKey())
            ->acceptJson()
            ->get($this->baseUrl() . '/transaction/verify/' . urlencode($reference));

        return $response->json();
    }

    public function isValidWebhookSignature(string $payload, ?string $signature): bool
    {
        if (!$signature) {
            return false;
        }

        $expected = hash_hmac('sha512', $payload, $this->secretKey());

        return hash_equals($expected, $signature);
    }

    protected function secretKey(): string
    {
        return (string) config('paystack.secret_key', config('services.paystack.secret_key'));
    }

    protected function baseUrl(): string
    {
        return rtrim((string) config('paystack.payment_url', config('services.paystack.payment_url', 'https://api.paystack.co')), '/');
    }
}
