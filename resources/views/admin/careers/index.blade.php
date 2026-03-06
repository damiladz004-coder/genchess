<x-app-layout>
    <div class="space-y-6 max-w-6xl mx-auto">
        <div class="flex items-center justify-between gap-3">
            <h1 class="text-3xl gc-heading">Careers Management</h1>
            <a href="{{ route('admin.dashboard') }}" class="gc-btn-secondary">Back to Dashboard</a>
        </div>

        @if(session('success'))
            <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="gc-panel p-4">
                <h2 class="text-lg font-semibold mb-3">Create Job Posting</h2>
                <form method="POST" action="{{ route('admin.careers.jobs.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium">Title</label>
                        <input name="title" required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium">Location</label>
                            <input name="location">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Type</label>
                            <select name="type">
                                <option value="">Select</option>
                                <option value="full-time">Full-time</option>
                                <option value="part-time">Part-time</option>
                                <option value="contract">Contract</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Description</label>
                        <textarea name="description" rows="4"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Requirements</label>
                        <textarea name="requirements" rows="3"></textarea>
                    </div>
                    <button type="submit" class="gc-btn-primary">Publish Job</button>
                </form>
            </div>

            <div class="gc-panel p-4">
                <h2 class="text-lg font-semibold mb-3">Job Postings</h2>
                @if($jobs->isEmpty())
                    <p class="text-slate-600">No job postings yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="gc-table min-w-full">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jobs as $job)
                                    <tr>
                                        <td>{{ $job->title }}</td>
                                        <td>{{ $job->active ? 'Active' : 'Inactive' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="gc-panel p-4">
            <h2 class="text-lg font-semibold mb-3">Recent Applications</h2>
            @if($applications->isEmpty())
                <p class="text-slate-600">No applications yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="gc-table min-w-full">
                        <thead>
                            <tr>
                                <th>Applicant</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>CV</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $app)
                                <tr>
                                    <td>
                                        {{ $app->name }}<br>
                                        <span class="text-xs text-slate-500">{{ $app->email }}</span>
                                    </td>
                                    <td>{{ $app->job->title ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($app->status) }}</td>
                                    <td>
                                        @if($app->cv_path)
                                            <a class="text-brand-700 underline"
                                               href="{{ route('admin.careers.applications.cv', $app) }}">
                                                Download
                                            </a>
                                        @else
                                            <span class="text-slate-500">No CV</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.careers.applications.update', $app) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status">
                                                <option value="new" @if($app->status === 'new') selected @endif>New</option>
                                                <option value="reviewed" @if($app->status === 'reviewed') selected @endif>Reviewed</option>
                                                <option value="interview" @if($app->status === 'interview') selected @endif>Interview</option>
                                                <option value="accepted" @if($app->status === 'accepted') selected @endif>Accepted</option>
                                                <option value="rejected" @if($app->status === 'rejected') selected @endif>Rejected</option>
                                            </select>
                                            <input name="notes" placeholder="Notes">
                                            <button type="submit" class="gc-btn-secondary text-xs px-3 py-1.5">Update</button>
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
