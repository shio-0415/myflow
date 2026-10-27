<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $task->subtasks()->create([
            'title' => $validated['title'],
            'status_id' => Status::NOT_STARTED,
        ]);

        $task->syncProgressFromSubtasks();

        return redirect()->route('tasks.show', $task);
    }

    public function update(Request $request, Subtask $subtask)
    {
        $task = $subtask->task;
        $this->authorize('update', $task);

        $validated = $request->validate([
            'status_id' => ['required', 'exists:statuses,id'],
        ]);

        $subtask->update(['status_id' => $validated['status_id']]);

        $task->syncProgressFromSubtasks();

        return redirect()->route('tasks.show', $task);
    }

    public function destroy(Subtask $subtask)
    {
        $task = $subtask->task;
        $this->authorize('update', $task);

        $subtask->delete();

        $task->syncProgressFromSubtasks();

        return redirect()->route('tasks.show', $task);
    }
}
