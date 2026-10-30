<?php

namespace App\Console\Commands;

use App\Mail\DeadlineReminder;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDeadlineReminders extends Command
{
    protected $signature = 'reminders:deadline';

    protected $description = '明日が期限の未完了・進行中のタスクを、通知がオンのユーザーにメールする';

    public function handle(): int
    {
        $start = now()->addDay()->startOfDay();
        $end = now()->addDay()->endOfDay();

        $users = User::where('notify_deadline', true)
            ->whereNotNull('notify_email')
            ->get();

        $sent = 0;

        foreach ($users as $user) {
            $tasks = Task::with('status')
                ->where('user_id', $user->id)
                ->whereBetween('due_date', [$start, $end])
                ->whereIn('status_id', [Status::NOT_STARTED, Status::IN_PROGRESS])
                ->orderBy('due_date')
                ->get();

            if ($tasks->isEmpty()) {
                continue;
            }

            Mail::to($user->notify_email)->send(new DeadlineReminder($user, $tasks));
            $this->info("送信：{$user->name}さん宛て {$tasks->count()}件");
            $sent++;
        }

        $this->info("完了：{$sent}人に送信しました。");

        return self::SUCCESS;
    }
}
