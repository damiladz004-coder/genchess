<x-app-layout>
    <div class="py-6 max-w-6xl mx-auto space-y-6">
        <h1 class="text-2xl font-bold">Store Categories</h1>

        <div class="bg-white border rounded p-4">
            <h2 class="text-lg font-semibold mb-3">Create Category</h2>
            <form method="POST" action="{{ route('admin.store.categories.store') }}" enctype="multipart/form-data" class="grid md:grid-cols-2 gap-3">
                @csrf
                <input name="title" class="border px-3 py-2" placeholder="Title" required>
                <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" class="border px-3 py-2">
                <textarea name="description" class="border px-3 py-2 md:col-span-2" rows="2" placeholder="Description"></textarea>
                <select name="status" class="border px-3 py-2">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <button class="bg-blue-600 text-white px-4 py-2 rounded" type="submit">Save</button>
            </form>
        </div>

        <div class="bg-white border rounded p-4 overflow-x-auto">
            <table class="min-w-full">
                <thead><tr><th class="text-left">Title</th><th class="text-left">Slug</th><th class="text-left">Status</th><th class="text-left">Action</th></tr></thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr class="border-t">
                            <td>{{ $category->title }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->status }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.store.categories.update', $category) }}" enctype="multipart/form-data" class="flex gap-2 items-center">
                                    @csrf
                                    @method('PATCH')
                                    <input name="title" value="{{ $category->title }}" class="border px-2 py-1">
                                    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" class="border px-2 py-1">
                                    <select name="status" class="border px-2 py-1">
                                        <option value="active" @selected($category->status==='active')>active</option>
                                        <option value="inactive" @selected($category->status==='inactive')>inactive</option>
                                    </select>
                                    <button class="text-blue-600 underline text-sm">Update</button>
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
