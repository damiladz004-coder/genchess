@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-xl">
    <h2 class="text-xl font-bold mb-2">Change Your Password</h2>
    <p class="text-gray-600 mb-6">
        For security, you must change your password before continuing.
    </p>

    @if(session('success'))
        <div class="mb-4 text-green-600">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.force.update') }}" class="bg-white border rounded p-4">
        @csrf
        <div class="grid gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Current Password</label>
                <input type="password" name="current_password" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">New Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>
        <button type="submit" class="mt-4 bg-blue-700 text-white px-4 py-2 rounded">
            Update Password
        </button>
    </form>
</div>
@endsection
