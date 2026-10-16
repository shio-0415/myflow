@props(['task', 'favorited' => false])

<a href="{{ route('tasks.show', $task) }}" class="block bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
    @if ($task->image_path)
        <img src="{{ asset('storage/' . $task->image_path) }}" alt="{{ $task->title }}" class="w-full h-32 object-cover">
    @endif

    <div class="p-4 space-y-2">
        <div class="flex items-start justify-between gap-2">
            <h3 class="font-bold text-gray-900 break-words">{{ $task->title }}</h3>
            @if ($favorited)
                <span class="text-yellow-500 text-xl leading-none" aria-label="重要">★</span>
            @endif
        </div>

        <p class="text-sm text-gray-600">期限：{{ $task->due_date->format('Y/m/d H:i') }}</p>

        <div class="flex flex-wrap items-center gap-2 text-xs">
            <span class="px-2 py-1 rounded-full {{ $task->tag->name === '就活' ? 'bg-purple-100 text-purple-800' : 'bg-amber-100 text-amber-800' }}">{{ $task->tag->name }}</span>
            <span class="px-2 py-1 rounded-full {{ $task->status_id === 3 ? 'bg-green-100 text-green-800' : ($task->status_id === 2 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">{{ $task->status->name }}</span>
        </div>
    </div>
</a>
