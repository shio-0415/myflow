<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">設定</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('settings.update') }}" class="bg-white p-6 shadow-sm rounded-lg space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <h3 class="font-semibold mb-2">警告</h3>
                    <label for="daily_work_minutes" class="block font-medium">1日に作業できる時間</label>
                    <select id="daily_work_minutes" name="daily_work_minutes" class="w-full border-gray-300 rounded">
                        @for ($m = 30; $m <= 720; $m += 30)
                            @php
                                $h = intdiv($m, 60);
                                $r = $m % 60;
                                $label = ($h > 0 ? $h . '時間' : '') . ($r > 0 ? $r . '分' : '');
                            @endphp
                            <option value="{{ $m }}" @selected((int) old('daily_work_minutes', $user->daily_work_minutes) === $m)>{{ $label }}</option>
                        @endfor
                    </select>
                    @error('daily_work_minutes') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    <p class="mt-2 text-sm text-gray-600">「期限までの日数 × この時間」が、タスクの残りの工数より少ないと、警告を出します。</p>
                </div>

                <div class="pt-4 border-t border-gray-200 space-y-3">
                    <h3 class="font-semibold">メール通知</h3>

                    <label class="flex items-start gap-2">
                        <input type="checkbox" name="notify_warning" value="1" @checked(old('notify_warning', $user->notify_warning)) class="mt-1 rounded border-gray-300">
                        <span>
                            <span class="block">警告が出たらすぐ通知</span>
                            <span class="block text-sm text-gray-500">工数が期限を超えそうになった時点で、メールします。</span>
                        </span>
                    </label>

                    <label class="flex items-start gap-2">
                        <input type="checkbox" name="notify_deadline" value="1" @checked(old('notify_deadline', $user->notify_deadline)) class="mt-1 rounded border-gray-300">
                        <span>
                            <span class="block">期限の前日に通知</span>
                            <span class="block text-sm text-gray-500">前日の20:00に、未完了・進行中のタスクをお知らせします。</span>
                        </span>
                    </label>

                    <div>
                        <label for="notify_email" class="block font-medium">通知先メールアドレス</label>
                        <input id="notify_email" name="notify_email" type="email" value="{{ old('notify_email', $user->notify_email) }}" class="w-full border-gray-300 rounded">
                        @error('notify_email') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <p class="text-sm text-gray-500">※ メールの送信は、現在、準備中です。設定は保存されますが、まだ届きません。</p>
                </div>

                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded">保存する</button>
            </form>
        </div>
    </div>
</x-app-layout>
