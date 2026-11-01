<p>{{ $user->name }} さん</p>

<p>期限に間に合わない可能性のあるタスクが {{ $tasks->count() }} 件あります。</p>

<ul>
@foreach ($tasks as $task)
    <li>
        {{ $task->title }}（期限：{{ $task->due_date->format('m/d H:i') }}）<br>
        確保できる時間：{{ \App\Models\Task::formatMinutes($task->availableMinutes($user->daily_work_minutes)) }}
        ／ 残りの工数：{{ \App\Models\Task::formatMinutes($task->remainingEffortMinutes()) }}
    </li>
@endforeach
</ul>

<p>MyFlow：{{ url('/tasks') }}</p>
