<?php

namespace App\Services;

use App\Models\SchoolRequest;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class SchoolOnboardingLinkService
{
    public function issue(SchoolRequest $schoolRequest, int $ttlHours = 168): string
    {
        $plainToken = Str::random(64);
        $expiresAt = now()->addHours($ttlHours);

        $schoolRequest->forceFill([
            'onboarding_token_hash' => hash('sha256', $plainToken),
            'onboarding_token_expires_at' => $expiresAt,
        ])->save();

        return URL::temporarySignedRoute(
            'school.portal.onboarding.create',
            $expiresAt,
            [
                'schoolRequest' => $schoolRequest->id,
                'token' => $plainToken,
            ]
        );
    }

    public function isValid(SchoolRequest $schoolRequest, ?string $plainToken): bool
    {
        $storedHash = (string) ($schoolRequest->onboarding_token_hash ?? '');
        $expiresAt = $schoolRequest->onboarding_token_expires_at;

        if ($storedHash === '') {
            // Backward compatibility for old links created before token rollout.
            return true;
        }

        if (!$plainToken) {
            return false;
        }

        if ($expiresAt && now()->greaterThan($expiresAt)) {
            return false;
        }

        return hash_equals($storedHash, hash('sha256', $plainToken));
    }

    public function consume(SchoolRequest $schoolRequest): void
    {
        $schoolRequest->forceFill([
            'onboarding_token_hash' => null,
            'onboarding_token_expires_at' => null,
        ])->save();
    }
}
