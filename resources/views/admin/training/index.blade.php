<x-app-layout>
    <div class="space-y-6 max-w-6xl mx-auto">
        <div class="flex items-center justify-between gap-3">
            <h1 class="text-3xl gc-heading">Instructor Training Portal</h1>
            <a href="{{ route('admin.dashboard') }}" class="gc-btn-secondary">Back to Dashboard</a>
        </div>

        @if(session('success'))
            <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div class="gc-panel p-4">
                <div class="text-xs text-slate-500">Paid Enrollments</div>
                <div class="text-3xl gc-heading">{{ $stats['total_enrollments'] }}</div>
            </div>
            <div class="gc-panel p-4">
                <div class="text-xs text-slate-500">Revenue</div>
                <div class="text-3xl gc-heading">NGN {{ number_format($stats['revenue_kobo'] / 100, 2) }}</div>
            </div>
            <div class="gc-panel p-4">
                <div class="text-xs text-slate-500">Coupon Redemptions</div>
                <div class="text-3xl gc-heading">{{ $stats['coupon_redemptions'] }}</div>
            </div>
            <div class="gc-panel p-4">
                <div class="text-xs text-slate-500">Active Coupons</div>
                <div class="text-3xl gc-heading">{{ $stats['active_coupons'] }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="gc-panel p-4">
                <h2 class="text-lg font-semibold mb-3">Create Course</h2>
                <form method="POST" action="{{ route('admin.training.courses.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium">Title</label>
                        <input name="title"  required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Description</label>
                        <textarea name="description"  rows="3"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Duration (hours and minutes)</label>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="number" name="duration_hours"  value="4" min="0" max="500" required placeholder="Hours">
                            <input type="number" name="duration_minutes"  value="0" min="0" max="59" required placeholder="Minutes">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium">Standard Price (NGN)</label>
                            <input type="number" step="0.01" name="price_naira"  value="35000">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Discount Price (NGN)</label>
                            <input type="number" step="0.01" name="discount_price_naira"  value="25000">
                        </div>
                    </div>
                    <button type="submit" class="gc-btn-primary">
                        Save Course
                    </button>
                </form>
            </div>

            <div class="gc-panel p-4">
                <h2 class="text-lg font-semibold mb-3">Create Cohort</h2>
                <form method="POST" action="{{ route('admin.training.cohorts.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium">Course</label>
                        <select name="course_id"  required>
                            <option value="">Select course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">Genchess Certified Chess Instructor Program (GCCIP)</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Cohort Name</label>
                        <input name="name"  required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium">Start Date</label>
                            <input type="date" name="start_date" >
                        </div>
                        <div>
                            <label class="block text-sm font-medium">End Date</label>
                            <input type="date" name="end_date" >
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Status</label>
                        <select name="status"  required>
                            <option value="planned">Planned</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <button type="submit" class="gc-btn-primary">
                        Save Cohort
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-8 gc-panel p-4">
            <h2 class="text-lg font-semibold mb-3">Cohorts</h2>
            @if($cohorts->isEmpty())
                <p class="text-slate-600">No cohorts yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="gc-table min-w-full">
                        <thead >
                            <tr>
                                <th >Course</th>
                                <th >Cohort</th>
                                <th >Dates</th>
                                <th >Status</th>
                                <th >Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cohorts as $cohort)
                                <tr >
                                    <td class="px-4 py-2">Genchess Certified Chess Instructor Program (GCCIP)</td>
                                    <td class="px-4 py-2">{{ $cohort->name }}</td>
                                    <td class="px-4 py-2">
                                        {{ $cohort->start_date?->format('Y-m-d') ?? '-' }}
                                        to
                                        {{ $cohort->end_date?->format('Y-m-d') ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2">{{ ucfirst($cohort->status) }}</td>
                                    <td class="px-4 py-2">
                                        <a class="gc-btn-secondary" href="{{ route('admin.training.cohorts.show', $cohort) }}">
                                            Manage
                                        </a>
                                        <span class="mx-1 text-slate-400">|</span>
                                        <a class="gc-btn-secondary" href="{{ route('admin.training.courses.curriculum', $cohort->course_id) }}">
                                            Curriculum
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="gc-panel p-4">
                <h2 class="text-lg font-semibold mb-3">Create Coupon</h2>
                <form method="POST" action="{{ route('admin.training.coupons.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium">Code</label>
                        <input name="code"  placeholder="EARLYBIRD2026" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Type</label>
                        <select name="type"  required>
                            <option value="early_bird">Early Bird</option>
                            <option value="referral">Referral</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium">Usage Limit</label>
                            <input type="number" name="usage_limit"  min="1">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Expiry Date</label>
                            <input type="date" name="expiry_date" >
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Status</label>
                        <select name="status"  required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="gc-btn-primary">Save Coupon</button>
                </form>
            </div>

            <div class="gc-panel p-4">
                <h2 class="text-lg font-semibold mb-3">Manually Assign Coupon</h2>
                <form method="POST" action="{{ route('admin.training.coupons.assign') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium">User</label>
                        <select name="user_id"  required>
                            <option value="">Select user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Expiry Date</label>
                        <input type="date" name="expiry_date" >
                    </div>
                    <button type="submit" class="gc-btn-primary">Generate 10,000 Discount Coupon</button>
                </form>
            </div>
        </div>

        <div class="mt-8 gc-panel p-4">
            <h2 class="text-lg font-semibold mb-3">Coupon Usage</h2>
            <div class="overflow-x-auto">
                <table class="gc-table min-w-full">
                    <thead >
                        <tr>
                            <th >Code</th>
                            <th >Type</th>
                            <th >Used</th>
                            <th >Limit</th>
                            <th >Expiry</th>
                            <th >Status</th>
                            <th >Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coupons as $coupon)
                            <tr >
                                <td class="px-4 py-2 font-medium">{{ $coupon->code }}</td>
                                <td class="px-4 py-2">{{ $coupon->type }}</td>
                                <td class="px-4 py-2">{{ $coupon->used_count }}</td>
                                <td class="px-4 py-2">{{ $coupon->usage_limit ?? 'Unlimited' }}</td>
                                <td class="px-4 py-2">{{ $coupon->expiry_date?->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-4 py-2">{{ ucfirst($coupon->status) }}</td>
                                <td class="px-4 py-2">
                                    <form method="POST" action="{{ route('admin.training.coupons.update', $coupon) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="border px-2 py-1 text-sm">
                                            <option value="active" @selected($coupon->status === 'active')>Active</option>
                                            <option value="inactive" @selected($coupon->status === 'inactive')>Inactive</option>
                                        </select>
                                        <button type="submit" class="gc-btn-secondary text-xs px-3 py-1.5">Update</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 gc-panel p-4">
            <h2 class="text-lg font-semibold mb-3">Referral Leaderboard (Paid Referrals)</h2>
            @if($referralLeaderboard->isEmpty())
                <p class="text-slate-600">No paid referrals yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="gc-table min-w-full">
                        <thead >
                            <tr>
                                <th >Referrer</th>
                                <th >Email</th>
                                <th >Paid Referrals</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($referralLeaderboard as $row)
                                <tr >
                                    <td class="px-4 py-2">{{ $row->referrer->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $row->referrer->email ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 font-semibold">{{ $row->paid_referrals }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

