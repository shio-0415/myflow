<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">タスクを登録する</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('tasks.store') }}" enctype="multipart/form-data" class="bg-white p-6 shadow-sm sm:rounded-lg space-y-4">
                @csrf

                <div>
                    <label for="title" class="block font-medium">タイトル（必須）</label>
                    <input id="title" name="title" type="text" value="{{ old('title') }}" class="w-full border-gray-300 rounded">
                    @error('title') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="due_date" class="block font-medium">期限（必須）</label>
                    <input id="due_date" name="due_date" type="datetime-local" value="{{ old('due_date') }}" class="w-full border-gray-300 rounded">
                    @error('due_date') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tag_id" class="block font-medium">タグ（必須）</label>
                    <select id="tag_id" name="tag_id" class="w-full border-gray-300 rounded">
                        <option value="">選んでください</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" @selected(old('tag_id') == $tag->id)>{{ $tag->name }}</option>
                        @endforeach
                    </select>
                    @error('tag_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status_id" class="block font-medium">状態</label>
                    <select id="status_id" name="status_id" class="w-full border-gray-300 rounded">
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}" @selected(old('status_id', 1) == $status->id)>{{ $status->name }}</option>
                        @endforeach
                    </select>
                    @error('status_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="estimated_minutes" class="block font-medium">所要時間（分）</label>
                    <input id="estimated_minutes" name="estimated_minutes" type="number" value="{{ old('estimated_minutes') }}" class="w-full border-gray-300 rounded">
                    @error('estimated_minutes') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="image" class="block font-medium">画像（任意・2MBまで）</label>
                    <input id="image" name="image" type="file" accept="image/*">
                    @error('image') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded">タスクを作成する</button>
                    <a href="{{ route('tasks.index') }}" class="px-4 py-2 border rounded">一覧に戻る</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
