@extends('layouts.public')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <h2 class="text-2xl font-bold mb-4">Verify Certificate</h2>
    <p class="text-gray-600 mb-6">Enter the certificate code to verify authenticity.</p>

    <form method="POST" action="{{ route('certificate.verify.show') }}" class="flex items-center gap-3 mb-6">
        @csrf
        <input name="code" value="{{ $code ?? '' }}" class="border px-3 py-2 w-full" placeholder="e.g., ABCD1234EF" required>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Verify</button>
    </form>

    @if(isset($certification))
        @if($certification)
            <div class="bg-green-50 border border-green-200 p-4 rounded">
                <p class="text-green-700 font-semibold">Certificate is valid.</p>
                <div class="mt-3 text-sm text-gray-700">
                    <div><strong>Name:</strong> {{ $certification->enrollment->user->name ?? 'N/A' }}</div>
                    <div><strong>Course:</strong> {{ $certification->enrollment->cohort->course->title ?? 'N/A' }}</div>
                    <div><strong>Cohort:</strong> {{ $certification->enrollment->cohort->name ?? 'N/A' }}</div>
                    <div><strong>Issued:</strong> {{ $certification->issued_at?->format('Y-m-d') ?? '-' }}</div>
                    <div><strong>Code:</strong> {{ $certification->certificate_code }}</div>
                </div>
            </div>
        @else
            <div class="bg-red-50 border border-red-200 p-4 rounded">
                <p class="text-red-700 font-semibold">Certificate not found.</p>
            </div>
        @endif
    @endif
</div>
@endsection
