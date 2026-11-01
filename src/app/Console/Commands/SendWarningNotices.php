<?php

namespace App\Console\Commands;

use App\Mail\WarningNotice;
use App\Models\Task;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWarningNotices extends Command
{
    protected $signature = 'reminders:warning';

    protected $description = '間に合わない可能性のあるタスクを、通知がオンのユーザーにメールする（同じタスクは1回だけ）';

    public function handle(): int
    {
        $users = User::where('notify_warning', true)
            ->whereNotNull('notify_email')
            ->get();

        $sent = 0;

        foreach ($users as $user) {
            $newlyAtRisk = collect();

            foreach (Task::with('status')->where('user_id', $user->id)->get() as $task) {
                $atRisk = $task->isAtRisk($user->daily_work_minutes);

                if ($atRisk && $task->warning_notified_at === null) {
                    $newlyAtRisk->push($task);
                } elseif (! $atRisk && $task->warning_notified_at !== null) {
                    $task->forceFill(['warning_notified_at' => null])->save();
                }
            }

            if ($newlyAtRisk->isEmpty()) {
                continue;
            }

            Mail::to($user->notify_email)->send(new WarningNotice($user, $newlyAtRisk));

            foreach ($newlyAtRisk as $task) {
                $task->forceFill(['warning_notified_at' => now()])->save();
            }

            $this->info("送信：{$user->name}さん宛て {$newlyAtRisk->count()}件");
            $sent++;
        }

        $this->info("完了：{$sent}人に送信しました。");

        return self::SUCCESS;
    }
}
