<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['就活', '学校'] as $name) {
            Tag::firstOrCreate(['name' => $name]);
        }

        foreach (['未着手', '進行中', '完了'] as $name) {
            Status::firstOrCreate(['name' => $name]);
        }
    }
}
