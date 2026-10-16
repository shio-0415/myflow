<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Tag;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['tag', 'status'])
            ->where('user_id', auth()->id())
            ->orderBy('due_date')
            ->paginate(5);

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $tags = Tag::all();
        $statuses = Status::all();

        return view('tasks.create', compact('tags', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'date'],
            'tag_id' => ['required', 'exists:tags,id'],
            'status_id' => ['nullable', 'exists:statuses,id'],
            'estimated_minutes' => ['nullable', 'integer', 'min:1', 'max:6000'],
            'progress' => ['nullable', 'integer', 'min:0', 'max:100'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('tasks', 'public');
        }
        unset($validated['image']);

        $validated['user_id'] = auth()->id();
        $validated['status_id'] = $validated['status_id'] ?? 1;
        $validated['progress'] = $validated['progress'] ?? 0;

        Task::create($validated);

        return redirect()->route('tasks.index')->with('status', 'タスクを登録しました。');
    }

    public function show(Task $task)
    {
        abort_unless($task->user_id === auth()->id(), 403);

        $task->load(['tag', 'status']);

        return view('tasks.show', compact('task'));
    }
}
