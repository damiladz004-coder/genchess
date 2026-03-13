@extends('layouts.public')

@section('content')
<section class="py-16 bg-white">
    <div class="mx-auto max-w-3xl px-6">
        <div class="gc-panel p-6 md:p-8 space-y-6">
            <div>
                <h1 class="gc-heading text-3xl">School Portal Registration</h1>
                <p class="mt-2 text-slate-600">
                    Complete onboarding for <strong>{{ $schoolRequest->school_name }}</strong> to access the school dashboard.
                </p>
            </div>

            @if($errors->any())
                <div class="rounded-lg border border-rose-200 bg-rose-50 p-4 text-rose-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ request()->fullUrl() }}" class="grid gap-4 md:grid-cols-2">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Administrator Name</label>
                    <input type="text" name="name" value="{{ old('name', $schoolRequest->contact_person) }}" required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $schoolRequest->email) }}" required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Phone / WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone', $schoolRequest->phone) }}" required>
                </div>
                <div></div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Password</label>
                    <input type="password" name="password" required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" required>
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="gc-btn-primary">Create School Portal Account</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
