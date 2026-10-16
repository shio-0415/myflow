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

            <form method="GET" action="{{ route('tasks.index') }}" class="mb-4 p-4 bg-white shadow-sm sm:rounded-lg grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div>
                    <label for="search" class="block text-sm font-medium">キーワード</label>
                    <input id="search" name="search" type="text" value="{{ request('search') }}" class="w-full border-gray-300 rounded">
                </div>

                <div>
                    <label for="tag_id" class="block text-sm font-medium">タグ</label>
                    <select id="tag_id" name="tag_id" class="w-full border-gray-300 rounded">
                        <option value="">すべて</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" @selected(request('tag_id') == $tag->id)>{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status_id" class="block text-sm font-medium">状態</label>
                    <select id="status_id" name="status_id" class="w-full border-gray-300 rounded">
                        <option value="">すべて</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}" @selected(request('status_id') == $status->id)>{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="sort" class="block text-sm font-medium">並び順</label>
                    <select id="sort" name="sort" class="w-full border-gray-300 rounded">
                        <option value="due_asc" @selected(request('sort', 'due_asc') === 'due_asc')>期限が近い順</option>
                        <option value="due_desc" @selected(request('sort') === 'due_desc')>期限が遠い順</option>
                    </select>
                </div>

                <div class="flex gap-3 sm:col-span-2 lg:col-span-4">
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded">絞り込む</button>
                    <a href="{{ route('tasks.index') }}" class="px-4 py-2 border rounded">リセット</a>
                </div>
            </form>

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
                    <div class="p-4 text-gray-600">
                        @if (request()->hasAny(['search', 'tag_id', 'status_id']))
                            条件に合うタスクがありません。
                        @else
                            まだタスクがありません。
                        @endif
                    </div>
                @endforelse
            </div>

            <div class="mt-4">{{ $tasks->links() }}</div>
        </div>
    </div>
</x-app-layout>
