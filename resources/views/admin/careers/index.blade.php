<x-app-layout>
    <div class="py-6 max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Careers Management</h1>
            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 underline">Back to Dashboard</a>
        </div>

        @if(session('success'))
            <div class="mb-4 text-green-700 bg-green-50 border border-green-200 px-4 py-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 text-red-700 bg-red-50 border border-red-200 px-4 py-2 rounded">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white border rounded p-4">
                <h2 class="text-lg font-semibold mb-3">Create Job Posting</h2>
                <form method="POST" action="{{ route('admin.careers.jobs.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium">Title</label>
                        <input name="title" class="border w-full px-3 py-2" required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium">Location</label>
                            <input name="location" class="border w-full px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Type</label>
                            <select name="type" class="border w-full px-3 py-2">
                                <option value="">Select</option>
                                <option value="full-time">Full-time</option>
                                <option value="part-time">Part-time</option>
                                <option value="contract">Contract</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Description</label>
                        <textarea name="description" class="border w-full px-3 py-2" rows="4"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Requirements</label>
                        <textarea name="requirements" class="border w-full px-3 py-2" rows="3"></textarea>
                    </div>
                    <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded">
                        Publish Job
                    </button>
                </form>
            </div>

            <div class="bg-white border rounded p-4">
                <h2 class="text-lg font-semibold mb-3">Job Postings</h2>
                @if($jobs->isEmpty())
                    <p class="text-gray-600">No job postings yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="text-left px-4 py-2 border-b">Title</th>
                                    <th class="text-left px-4 py-2 border-b">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jobs as $job)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $job->title }}</td>
                                        <td class="px-4 py-2">{{ $job->active ? 'Active' : 'Inactive' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-8 bg-white border rounded p-4">
            <h2 class="text-lg font-semibold mb-3">Recent Applications</h2>
            @if($applications->isEmpty())
                <p class="text-gray-600">No applications yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-2 border-b">Applicant</th>
                                <th class="text-left px-4 py-2 border-b">Role</th>
                                <th class="text-left px-4 py-2 border-b">Status</th>
                                <th class="text-left px-4 py-2 border-b">CV</th>
                                <th class="text-left px-4 py-2 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $app)
                                <tr class="border-b">
                                    <td class="px-4 py-2">
                                        {{ $app->name }}<br>
                                        <span class="text-xs text-gray-500">{{ $app->email }}</span>
                                    </td>
                                    <td class="px-4 py-2">{{ $app->job->title ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ ucfirst($app->status) }}</td>
                                    <td class="px-4 py-2">
                                        @if($app->cv_path)
                                            <a class="text-blue-600 underline"
                                               href="{{ route('admin.careers.applications.cv', $app) }}">
                                                Download
                                            </a>
                                        @else
                                            <span class="text-gray-500">No CV</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        <form method="POST" action="{{ route('admin.careers.applications.update', $app) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="border px-2 py-1">
                                                <option value="new" @if($app->status === 'new') selected @endif>New</option>
                                                <option value="reviewed" @if($app->status === 'reviewed') selected @endif>Reviewed</option>
                                                <option value="interview" @if($app->status === 'interview') selected @endif>Interview</option>
                                                <option value="accepted" @if($app->status === 'accepted') selected @endif>Accepted</option>
                                                <option value="rejected" @if($app->status === 'rejected') selected @endif>Rejected</option>
                                            </select>
                                            <input name="notes" class="border px-2 py-1" placeholder="Notes">
                                            <button type="submit" class="bg-gray-900 text-white px-3 py-1 rounded">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
