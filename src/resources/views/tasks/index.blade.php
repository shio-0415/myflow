<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">タスク一覧</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
            @endif

            <div class="mb-4">
                <a href="{{ route('tasks.create') }}" class="inline-block px-4 py-2 bg-gray-800 text-white rounded">＋ タスクを登録する</a>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg divide-y">
                @forelse ($tasks as $task)
                    <a href="{{ route('tasks.show', $task) }}" class="block p-4 hover:bg-gray-50">
                        <div class="font-semibold">{{ $task->title }}</div>
                        <div class="text-sm text-gray-600">
                            期限：{{ $task->due_date->format('Y/m/d H:i') }}
                            ／ {{ $task->tag->name }}
                            ／ {{ $task->status->name }}
                        </div>
                    </a>
                @empty
                    <div class="p-4 text-gray-600">まだタスクがありません。</div>
                @endforelse
            </div>

            <div class="mt-4">{{ $tasks->links() }}</div>
        </div>
    </div>
</x-app-layout>
