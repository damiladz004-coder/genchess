<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\TrainingCourse;
use App\Models\TrainingCouponRedemption;
use App\Models\TrainingEnrollment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TrainingCouponService
{
    public function resolveDiscount(TrainingCourse $course, ?string $rawCode): array
    {
        $subtotal = (int) $course->price_kobo;
        $coupon = null;
        $discount = 0;
        $message = null;

        $code = $rawCode ? strtoupper(trim($rawCode)) : null;

        if ($code) {
            $coupon = Coupon::where('code', $code)->first();

            if (!$coupon || !$this->isValid($coupon)) {
                $coupon = null;
                $message = 'Coupon is invalid or expired.';
            } else {
                $discount = $coupon->discount_type === 'fixed_amount'
                    ? min((int) $coupon->discount_value, $subtotal)
                    : 0;
            }
        }

        return [
            'coupon' => $coupon,
            'subtotal_kobo' => $subtotal,
            'discount_kobo' => $discount,
            'total_kobo' => max(0, $subtotal - $discount),
            'message' => $message,
        ];
    }

    public function redeem(
        Coupon $coupon,
        User $user,
        ?TrainingEnrollment $enrollment,
        int $discountKobo
    ): TrainingCouponRedemption {
        return DB::transaction(function () use ($coupon, $user, $enrollment, $discountKobo): TrainingCouponRedemption {
            $lockedCoupon = Coupon::whereKey($coupon->id)->lockForUpdate()->firstOrFail();

            if (!$this->isValid($lockedCoupon)) {
                throw new \RuntimeException('Coupon is no longer valid.');
            }

            $existing = TrainingCouponRedemption::where('coupon_id', $lockedCoupon->id)
                ->where('user_id', $user->id)
                ->where('enrollment_id', $enrollment?->id)
                ->first();

            if ($existing) {
                return $existing;
            }

            $redemption = TrainingCouponRedemption::create([
                'coupon_id' => $lockedCoupon->id,
                'user_id' => $user->id,
                'enrollment_id' => $enrollment?->id,
                'discount_applied_kobo' => $discountKobo,
            ]);

            $lockedCoupon->increment('used_count');

            return $redemption;
        });
    }

    public function isValid(Coupon $coupon): bool
    {
        if ($coupon->status !== 'active') {
            return false;
        }

        if ($coupon->expiry_date && now()->greaterThan($coupon->expiry_date)) {
            return false;
        }

        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            return false;
        }

        return true;
    }
}

