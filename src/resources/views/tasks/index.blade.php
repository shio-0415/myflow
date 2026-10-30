<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">タスク一覧</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
            @endif

            <div class="mb-4">
                <a href="{{ route('tasks.create') }}" class="inline-block px-4 py-2 bg-gray-800 text-white rounded">＋ タスクを登録する</a>
            </div>

            <div class="mb-4 grid grid-cols-3 gap-3 text-center">
                <div class="p-3 bg-white shadow-sm rounded-lg"><p class="text-sm text-gray-600">未完了</p><p class="text-2xl font-bold">{{ $counts[\App\Models\Status::NOT_STARTED] ?? 0 }}</p></div>
                <div class="p-3 bg-white shadow-sm rounded-lg"><p class="text-sm text-gray-600">進行中</p><p class="text-2xl font-bold">{{ $counts[\App\Models\Status::IN_PROGRESS] ?? 0 }}</p></div>
                <div class="p-3 bg-white shadow-sm rounded-lg"><p class="text-sm text-gray-600">完了</p><p class="text-2xl font-bold">{{ $counts[\App\Models\Status::DONE] ?? 0 }}</p></div>
            </div>

            <form method="GET" action="{{ route('tasks.index') }}" class="mb-6 p-4 bg-white shadow-sm rounded-lg grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
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
                    <span class="block text-sm font-medium">状態（複数選べます）</span>
                    <div class="mt-1 flex flex-wrap gap-x-4 gap-y-1">
                        @foreach ($statuses as $status)
                            <label class="inline-flex items-center gap-1 text-sm">
                                <input type="checkbox" name="status_id[]" value="{{ $status->id }}" @checked(in_array((string) $status->id, array_map('strval', (array) request('status_id', [])), true)) class="rounded border-gray-300">
                                <span>{{ $status->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label for="sort" class="block text-sm font-medium">並び順</label>
                    <select id="sort" name="sort" class="w-full border-gray-300 rounded">
                        <option value="due_asc" @selected(request('sort', 'due_asc') === 'due_asc')>期限が近い順</option>
                        <option value="due_desc" @selected(request('sort') === 'due_desc')>期限が遠い順</option>
                        <option value="effort_desc" @selected(request('sort') === 'effort_desc')>工数が多い順</option>
                        <option value="effort_asc" @selected(request('sort') === 'effort_asc')>工数が少ない順</option>
                    </select>
                </div>

                <div class="flex gap-3 sm:col-span-2 lg:col-span-4">
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded">絞り込む</button>
                    <a href="{{ route('tasks.index') }}" class="px-4 py-2 border rounded">リセット</a>
                </div>
            </form>

            @if ($tasks->isEmpty())
                <div class="p-6 bg-white shadow-sm rounded-lg text-gray-600">
                    @if (request()->hasAny(['search', 'tag_id', 'status_id']))
                        条件に合うタスクがありません。
                    @else
                        まだタスクがありません。
                    @endif
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($tasks as $task)
                        <x-task-card :task="$task" :favorited="in_array($task->id, $favoriteIds)" :daily-minutes="$dailyMinutes" />
                    @endforeach
                </div>
            @endif

            <div class="mt-6">{{ $tasks->links() }}</div>
        </div>
    </div>
</x-app-layout>
