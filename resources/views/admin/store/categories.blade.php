<x-app-layout>
    <div class="space-y-6 max-w-6xl mx-auto">
        <h1 class="text-3xl gc-heading">Store Categories</h1>

        <div class="gc-panel p-4">
            <h2 class="text-lg font-semibold mb-3">Create Category</h2>
            <form method="POST" action="{{ route('admin.store.categories.store') }}" enctype="multipart/form-data" class="grid md:grid-cols-2 gap-3">
                @csrf
                <input name="title" placeholder="Title" required>
                <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
                <textarea name="description" class="md:col-span-2" rows="2" placeholder="Description"></textarea>
                <select name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <button class="gc-btn-primary" type="submit">Save</button>
            </form>
        </div>

        <div class="gc-panel p-4 overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->title }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->status }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.store.categories.update', $category) }}" enctype="multipart/form-data" class="flex gap-2 items-center">
                                    @csrf
                                    @method('PATCH')
                                    <input name="title" value="{{ $category->title }}">
                                    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
                                    <select name="status">
                                        <option value="active" @selected($category->status==='active')>active</option>
                                        <option value="inactive" @selected($category->status==='inactive')>inactive</option>
                                    </select>
                                    <button class="gc-btn-secondary text-xs px-3 py-1.5">Update</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $categories->links() }}</div>
        </div>
    </div>
</x-app-layout>
