<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">タスクの詳細</h2>
    </x-slot>

    @php
        $atRisk = $task->isAtRisk($dailyMinutes);
        $remaining = $task->remainingEffortMinutes();
        $available = $task->availableMinutes($dailyMinutes);
    @endphp

    <div class="py-8">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
            @endif

            <div class="bg-white p-6 shadow-sm rounded-lg space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <h3 class="text-lg font-semibold">{{ $task->title }}</h3>

                    <div x-data="{
                        favorited: {{ $isFavorited ? 'true' : 'false' }},
                        toggleFavorite() {
                            fetch('{{ route('tasks.favorite', $task) }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').content,
                                    'Accept': 'application/json',
                                }
                            })
                            .then(res => res.json())
                            .then(data => { this.favorited = data.favorited; });
                        }
                    }">
                        <button type="button" @click="toggleFavorite()"
                                :class="favorited ? 'text-yellow-500' : 'text-gray-400'"
                                class="text-3xl leading-none" aria-label="重要マーク">
                            <span x-text="favorited ? '★' : '☆'"></span>
                        </button>
                    </div>
                </div>

                @if ($atRisk)
                    <div class="p-3 bg-red-50 border border-red-300 rounded text-sm text-red-700 space-y-1">
                        <p class="font-semibold">⚠ 間に合わない可能性があります</p>
                        <p>期限までに確保できる時間：{{ \App\Models\Task::formatMinutes($available) }}</p>
                        <p>残りの工数：{{ \App\Models\Task::formatMinutes($remaining) }}</p>
                        <p>（1日の作業時間：{{ \App\Models\Task::formatMinutes($dailyMinutes) }}。<a href="{{ route('settings.edit') }}" class="underline">設定を変える</a>）</p>
                    </div>
                @endif

                <p>期限：{{ $task->due_date->format('Y/m/d H:i') }}</p>
                <p>タグ：{{ $task->tag->name }}</p>
                <p>状態：{{ $task->status->name }}</p>
                <p>工数：{{ $task->estimated_minutes ? \App\Models\Task::formatMinutes($task->estimated_minutes) : '未設定' }}</p>
                <p>進捗：{{ $task->progress }}%</p>
                @if ($task->completed_at)
                    <p>完了日時：{{ $task->completed_at->format('Y/m/d H:i') }}</p>
                @endif
                @if ($task->image_path)
                    <img src="{{ asset('storage/' . $task->image_path) }}" alt="添付画像" class="max-w-full rounded">
                @endif

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('tasks.index') }}" class="inline-block px-4 py-2 border rounded">一覧に戻る</a>
                    <a href="{{ route('tasks.edit', $task) }}" class="inline-block px-4 py-2 bg-gray-800 text-white rounded">編集</a>

                    <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('このタスクを削除します。よろしいですか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">削除</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
