@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-xl">
    <div>
        <h2 class="text-3xl gc-heading">Change Your Password</h2>
        <p class="text-slate-600 mt-2">For security, you must change your password before continuing.</p>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.force.update') }}" class="gc-panel p-4">
        @csrf
        <div class="grid gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Current Password</label>
                <input type="password" name="current_password" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">New Password</label>
                <input type="password" name="password" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Confirm New Password</label>
                <input type="password" name="password_confirmation" required>
            </div>
        </div>
        <button type="submit" class="mt-4 gc-btn-primary">Update Password</button>
    </form>
</div>
@endsection
