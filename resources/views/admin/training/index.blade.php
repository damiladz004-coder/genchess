<x-app-layout>
    <div class="py-6 max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Instructor Training Portal</h1>
            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 underline">Back to Dashboard</a>
        </div>

        @if(session('success'))
            <div class="mb-4 text-green-700 bg-green-50 border border-green-200 px-4 py-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white border rounded p-4">
                <div class="text-xs text-slate-500">Paid Enrollments</div>
                <div class="text-2xl font-bold">{{ $stats['total_enrollments'] }}</div>
            </div>
            <div class="bg-white border rounded p-4">
                <div class="text-xs text-slate-500">Revenue</div>
                <div class="text-2xl font-bold">₦{{ number_format($stats['revenue_kobo'] / 100, 2) }}</div>
            </div>
            <div class="bg-white border rounded p-4">
                <div class="text-xs text-slate-500">Coupon Redemptions</div>
                <div class="text-2xl font-bold">{{ $stats['coupon_redemptions'] }}</div>
            </div>
            <div class="bg-white border rounded p-4">
                <div class="text-xs text-slate-500">Active Coupons</div>
                <div class="text-2xl font-bold">{{ $stats['active_coupons'] }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white border rounded p-4">
                <h2 class="text-lg font-semibold mb-3">Create Course</h2>
                <form method="POST" action="{{ route('admin.training.courses.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium">Title</label>
                        <input name="title" class="border w-full px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Description</label>
                        <textarea name="description" class="border w-full px-3 py-2" rows="3"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Duration (hours and minutes)</label>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="number" name="duration_hours" class="border w-full px-3 py-2" value="4" min="0" max="500" required placeholder="Hours">
                            <input type="number" name="duration_minutes" class="border w-full px-3 py-2" value="0" min="0" max="59" required placeholder="Minutes">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium">Standard Price (NGN)</label>
                            <input type="number" step="0.01" name="price_naira" class="border w-full px-3 py-2" value="35000">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Discount Price (NGN)</label>
                            <input type="number" step="0.01" name="discount_price_naira" class="border w-full px-3 py-2" value="25000">
                        </div>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                        Save Course
                    </button>
                </form>
            </div>

            <div class="bg-white border rounded p-4">
                <h2 class="text-lg font-semibold mb-3">Create Cohort</h2>
                <form method="POST" action="{{ route('admin.training.cohorts.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium">Course</label>
                        <select name="course_id" class="border w-full px-3 py-2" required>
                            <option value="">Select course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Cohort Name</label>
                        <input name="name" class="border w-full px-3 py-2" required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium">Start Date</label>
                            <input type="date" name="start_date" class="border w-full px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">End Date</label>
                            <input type="date" name="end_date" class="border w-full px-3 py-2">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Status</label>
                        <select name="status" class="border w-full px-3 py-2" required>
                            <option value="planned">Planned</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded">
                        Save Cohort
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-8 bg-white border rounded p-4">
            <h2 class="text-lg font-semibold mb-3">Cohorts</h2>
            @if($cohorts->isEmpty())
                <p class="text-gray-600">No cohorts yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-2 border-b">Course</th>
                                <th class="text-left px-4 py-2 border-b">Cohort</th>
                                <th class="text-left px-4 py-2 border-b">Dates</th>
                                <th class="text-left px-4 py-2 border-b">Status</th>
                                <th class="text-left px-4 py-2 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cohorts as $cohort)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $cohort->course->title ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $cohort->name }}</td>
                                    <td class="px-4 py-2">
                                        {{ $cohort->start_date?->format('Y-m-d') ?? '-' }}
                                        to
                                        {{ $cohort->end_date?->format('Y-m-d') ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2">{{ ucfirst($cohort->status) }}</td>
                                    <td class="px-4 py-2">
                                        <a class="text-blue-600 underline" href="{{ route('admin.training.cohorts.show', $cohort) }}">
                                            Manage
                                        </a>
                                        <span class="mx-1 text-gray-400">|</span>
                                        <a class="text-blue-600 underline" href="{{ route('admin.training.courses.curriculum', $cohort->course_id) }}">
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
            <div class="bg-white border rounded p-4">
                <h2 class="text-lg font-semibold mb-3">Create Coupon</h2>
                <form method="POST" action="{{ route('admin.training.coupons.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium">Code</label>
                        <input name="code" class="border w-full px-3 py-2" placeholder="EARLYBIRD2026" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Type</label>
                        <select name="type" class="border w-full px-3 py-2" required>
                            <option value="early_bird">Early Bird</option>
                            <option value="referral">Referral</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium">Usage Limit</label>
                            <input type="number" name="usage_limit" class="border w-full px-3 py-2" min="1">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Expiry Date</label>
                            <input type="date" name="expiry_date" class="border w-full px-3 py-2">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Status</label>
                        <select name="status" class="border w-full px-3 py-2" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save Coupon</button>
                </form>
            </div>

            <div class="bg-white border rounded p-4">
                <h2 class="text-lg font-semibold mb-3">Manually Assign Coupon</h2>
                <form method="POST" action="{{ route('admin.training.coupons.assign') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium">User</label>
                        <select name="user_id" class="border w-full px-3 py-2" required>
                            <option value="">Select user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Expiry Date</label>
                        <input type="date" name="expiry_date" class="border w-full px-3 py-2">
                    </div>
                    <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded">Generate 10,000 Discount Coupon</button>
                </form>
            </div>
        </div>

        <div class="mt-8 bg-white border rounded p-4">
            <h2 class="text-lg font-semibold mb-3">Coupon Usage</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-4 py-2 border-b">Code</th>
                            <th class="text-left px-4 py-2 border-b">Type</th>
                            <th class="text-left px-4 py-2 border-b">Used</th>
                            <th class="text-left px-4 py-2 border-b">Limit</th>
                            <th class="text-left px-4 py-2 border-b">Expiry</th>
                            <th class="text-left px-4 py-2 border-b">Status</th>
                            <th class="text-left px-4 py-2 border-b">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coupons as $coupon)
                            <tr class="border-b">
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
                                        <button type="submit" class="text-blue-600 underline text-sm">Update</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 bg-white border rounded p-4">
            <h2 class="text-lg font-semibold mb-3">Referral Leaderboard (Paid Referrals)</h2>
            @if($referralLeaderboard->isEmpty())
                <p class="text-gray-600">No paid referrals yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-2 border-b">Referrer</th>
                                <th class="text-left px-4 py-2 border-b">Email</th>
                                <th class="text-left px-4 py-2 border-b">Paid Referrals</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($referralLeaderboard as $row)
                                <tr class="border-b">
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
