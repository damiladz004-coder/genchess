<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\TrainingReferral;
use App\Models\TrainingReferralReward;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TrainingReferralService
{
    public function attachReferral(User $user, ?string $referralCode): ?TrainingReferral
    {
        $code = $referralCode ? strtoupper(trim($referralCode)) : null;
        if (!$code) {
            return null;
        }

        $referrer = User::where('referral_code', $code)->first();
        if (!$referrer || $referrer->id === $user->id) {
            return null;
        }

        return TrainingReferral::firstOrCreate(
            [
                'referrer_id' => $referrer->id,
                'referred_user_id' => $user->id,
            ],
            [
                'payment_status' => 'pending',
                'reward_issued' => false,
            ]
        );
    }

    public function markReferredUserPaid(User $user): void
    {
        DB::transaction(function () use ($user): void {
            $referral = TrainingReferral::where('referred_user_id', $user->id)->lockForUpdate()->first();
            if (!$referral) {
                return;
            }

            if ($referral->payment_status !== 'paid') {
                $referral->update(['payment_status' => 'paid']);
            }

            $paidCount = TrainingReferral::where('referrer_id', $referral->referrer_id)
                ->where('payment_status', 'paid')
                ->count();

            if ($paidCount < 2) {
                return;
            }

            $existingReward = TrainingReferralReward::where('user_id', $referral->referrer_id)->exists();
            if ($existingReward) {
                return;
            }

            $coupon = Coupon::create([
                'code' => $this->generateUniqueRewardCouponCode(),
                'type' => 'referral',
                'discount_type' => 'fixed_amount',
                'discount_value' => 1000000,
                'usage_limit' => 1,
                'used_count' => 0,
                'expiry_date' => now()->addMonths(6),
                'status' => 'active',
            ]);

            TrainingReferralReward::create([
                'user_id' => $referral->referrer_id,
                'coupon_id' => $coupon->id,
                'qualified_paid_referrals' => $paidCount,
            ]);

            $referral->update(['reward_issued' => true]);
        });
    }

    protected function generateUniqueRewardCouponCode(): string
    {
        do {
            $code = 'REF-' . strtoupper(Str::random(8));
        } while (Coupon::where('code', $code)->exists());

        return $code;
    }
}

