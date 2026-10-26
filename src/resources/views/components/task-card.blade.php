@props(['task', 'favorited' => false, 'dailyMinutes' => 120])

@php
    $atRisk = $task->isAtRisk($dailyMinutes);
    $statusClass = match ((int) $task->status_id) {
        \App\Models\Status::DONE => 'bg-green-100 text-green-800',
        \App\Models\Status::IN_PROGRESS => 'bg-blue-100 text-blue-800',
        default => 'bg-gray-100 text-gray-800',
    };
@endphp

<a href="{{ route('tasks.show', $task) }}" class="block bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition {{ $atRisk ? 'ring-2 ring-red-400' : '' }}">
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

        @if ($atRisk)
            <p class="text-sm font-semibold text-red-600">⚠ 間に合わない可能性</p>
        @endif

        <p class="text-sm text-gray-600">期限：{{ $task->due_date->format('Y/m/d H:i') }}</p>

        @if ($task->estimated_minutes)
            <p class="text-sm text-gray-600">工数：{{ \App\Models\Task::formatMinutes($task->estimated_minutes) }}</p>
        @endif

        <div class="flex flex-wrap items-center gap-2 text-xs">
            <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-800">{{ $task->tag->name }}</span>
            <span class="px-2 py-1 rounded-full {{ $statusClass }}">{{ $task->status->name }}</span>
        </div>
    </div>
</a>
