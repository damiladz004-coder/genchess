<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl gc-heading">Notifications</h1>
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                @method('PATCH')
                <button class="gc-btn-secondary text-xs px-3 py-1.5" type="submit">Mark all as read</button>
            </form>
        </div>

        <div class="gc-panel p-4 space-y-3">
            @forelse($notifications as $notification)
                @php $data = $notification->data ?? []; @endphp
                <div class="border rounded p-3 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50 border-blue-200' }}">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="font-semibold text-sm">{{ $data['title'] ?? 'Notification' }}</div>
                            <div class="text-sm text-slate-700">{{ $data['message'] ?? '' }}</div>
                            <div class="text-xs text-slate-500 mt-1">{{ $notification->created_at?->diffForHumans() }}</div>
                        </div>
                        @if(!$notification->read_at)
                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                @csrf
                                @method('PATCH')
                                <button class="text-xs text-brand-700 underline" type="submit">Mark read</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-slate-600">No notifications yet.</p>
            @endforelse

            <div>{{ $notifications->links() }}</div>
        </div>
    </div>
</x-app-layout>

