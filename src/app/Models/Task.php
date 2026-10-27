<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'tag_id',
        'status_id',
        'title',
        'due_date',
        'estimated_minutes',
        'progress',
        'image_path',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function syncProgressFromSubtasks(): void
    {
        $total = $this->subtasks()->count();

        if ($total === 0) {
            return;
        }

        $done = $this->subtasks()->where('status_id', Status::DONE)->count();

        $this->update(['progress' => (int) round($done / $total * 100)]);
    }

    public function remainingEffortMinutes(): ?int
    {
        if ($this->estimated_minutes === null) {
            return null;
        }

        $progress = $this->progress ?? 0;

        return (int) round($this->estimated_minutes * (100 - $progress) / 100);
    }

    public function availableMinutes(int $dailyMinutes): int
    {
        $minutesUntilDue = now()->diffInMinutes($this->due_date, false);

        if ($minutesUntilDue <= 0) {
            return 0;
        }

        return (int) floor($minutesUntilDue / 1440 * $dailyMinutes);
    }

    public static function formatMinutes(int $minutes): string
    {
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;

        if ($h > 0 && $m > 0) {
            return $h . '時間' . $m . '分';
        }

        return $h > 0 ? $h . '時間' : $m . '分';
    }

    public function isAtRisk(int $dailyMinutes): bool
    {
        if ((int) $this->status_id === Status::DONE) {
            return false;
        }

        $remaining = $this->remainingEffortMinutes();

        if ($remaining === null || $remaining <= 0) {
            return false;
        }

        return $this->availableMinutes($dailyMinutes) < $remaining;
    }
}
