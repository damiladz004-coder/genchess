@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold">Certificate</h2>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.training.certificates.download', $certification) }}"
               class="bg-gray-800 text-white px-3 py-2 rounded">
                Download PDF
            </a>
            <a href="{{ route('admin.training.index') }}" class="text-blue-600 underline">Back to Training</a>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-4 text-red-700 bg-red-50 border border-red-200 px-4 py-2 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border-4 border-gray-800 p-10 relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="w-full h-full bg-[radial-gradient(circle_at_20%_20%,#000_1px,transparent_1px)] [background-size:24px_24px]"></div>
        </div>

        <div class="relative">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-900 text-white flex items-center justify-center font-bold">
                        G
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Genchess Academy</h1>
                        <p class="text-sm text-gray-600">Instructor Certification</p>
                    </div>
                </div>
                <div class="text-right text-xs text-gray-600">
                    <div>Certificate Code</div>
                    <div class="font-semibold text-gray-900">{{ $certification->certificate_code }}</div>
                </div>
            </div>

            <div class="mt-10 text-center">
                <p class="text-lg text-gray-700">This certifies that</p>
                <h2 class="text-3xl font-semibold mt-2">
                    {{ $certification->enrollment->user->name ?? 'Instructor' }}
                </h2>
                <p class="mt-4 text-gray-700">
                    has successfully completed the training program:
                </p>
                <p class="text-2xl font-semibold mt-2">
                    {{ $certification->enrollment->cohort->course->title ?? 'Course' }}
                </p>
                <p class="text-sm text-gray-600 mt-2">
                    Cohort: {{ $certification->enrollment->cohort->name ?? '-' }}
                </p>
            </div>

            <div class="mt-10 flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    <div>Issued</div>
                    <div class="font-semibold">{{ $certification->issued_at?->format('Y-m-d') ?? '-' }}</div>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full border-4 border-gray-800 mx-auto flex items-center justify-center">
                        <span class="text-xs font-semibold">SEAL</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">Genchess Official Seal</div>
                </div>
                <div class="text-sm text-gray-700 text-right">
                    <div>Director</div>
                    <div class="mt-6 border-t border-gray-700 w-40 ml-auto"></div>
                    <div class="text-xs text-gray-500">Authorized Signature</div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 text-sm text-gray-600">
        Verify this certificate at /verify-certificate
    </div>
</div>
@endsection
