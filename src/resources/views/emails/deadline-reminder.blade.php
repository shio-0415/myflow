<p>{{ $user->name }} さん</p>

<p>明日が期限のタスクが {{ $tasks->count() }} 件あります。</p>

<ul>
@foreach ($tasks as $task)
    <li>{{ $task->title }}（{{ $task->due_date->format('m/d H:i') }}・{{ $task->status->name }}）</li>
@endforeach
</ul>

<p>MyFlow：{{ url('/tasks') }}</p>
