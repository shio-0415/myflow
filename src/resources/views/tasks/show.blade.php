<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">タスクの詳細</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg space-y-3">
                <h3 class="text-lg font-semibold">{{ $task->title }}</h3>
                <p>期限：{{ $task->due_date->format('Y/m/d H:i') }}</p>
                <p>タグ：{{ $task->tag->name }}</p>
                <p>状態：{{ $task->status->name }}</p>
                <p>所要時間：{{ $task->estimated_minutes ? $task->estimated_minutes . '分' : '未設定' }}</p>
                <p>進捗：{{ $task->progress }}%</p>
                @if ($task->image_path)
                    <img src="{{ asset('storage/' . $task->image_path) }}" alt="添付画像" class="max-w-full rounded">
                @endif
                <a href="{{ route('tasks.index') }}" class="inline-block px-4 py-2 border rounded">一覧に戻る</a>
            </div>
        </div>
    </div>
</x-app-layout>
