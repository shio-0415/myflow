<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Status;
use App\Models\Tag;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['tag', 'status'])
            ->where('user_id', auth()->id());

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tag_id')) {
            $query->where('tag_id', $request->tag_id);
        }

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        $sort = $request->get('sort', 'due_asc');
        if ($sort === 'due_desc') {
            $query->orderBy('due_date', 'desc');
        } else {
            $query->orderBy('due_date', 'asc');
        }

        $tasks = $query->paginate(5)->withQueryString();
        $tags = Tag::all();
        $statuses = Status::all();

        $favoriteIds = auth()->user()->favoriteTasks()->pluck('tasks.id')->all();

        return view('tasks.index', compact('tasks', 'tags', 'statuses', 'favoriteIds'));
    }

    public function create()
    {
        $tags = Tag::all();
        $statuses = Status::all();

        return view('tasks.create', compact('tags', 'statuses'));
    }

    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();

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
        $this->authorize('view', $task);

        $task->load(['tag', 'status']);

        $isFavorited = auth()->user()->favoriteTasks()->where('task_id', $task->id)->exists();

        return view('tasks.show', compact('task', 'isFavorited'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $tags = Tag::all();
        $statuses = Status::all();

        return view('tasks.edit', compact('task', 'tags', 'statuses'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($task->image_path) {
                Storage::disk('public')->delete($task->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('tasks', 'public');
        }
        unset($validated['image']);

        $validated['progress'] = $validated['progress'] ?? 0;

        if ((int) $validated['status_id'] === 3 && $task->completed_at === null) {
            $validated['completed_at'] = now();
        } elseif ((int) $validated['status_id'] !== 3) {
            $validated['completed_at'] = null;
        }

        $task->update($validated);

        return redirect()->route('tasks.show', $task)->with('status', 'タスクを更新しました。');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        if ($task->image_path) {
            Storage::disk('public')->delete($task->image_path);
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('status', 'タスクを削除しました。');
    }
}
