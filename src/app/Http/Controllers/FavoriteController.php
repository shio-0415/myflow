<?php

namespace App\Http\Controllers;

use App\Models\Task;

class FavoriteController extends Controller
{
    public function toggle(Task $task)
    {
        $this->authorize('view', $task);

        $user = auth()->user();

        if ($user->favoriteTasks()->where('task_id', $task->id)->exists()) {
            $user->favoriteTasks()->detach($task->id);
            $favorited = false;
        } else {
            $user->favoriteTasks()->attach($task->id);
            $favorited = true;
        }

        return response()->json([
            'favorited' => $favorited,
        ]);
    }
}
