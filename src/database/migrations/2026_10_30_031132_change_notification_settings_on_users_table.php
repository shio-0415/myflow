<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notify_deadline')->default(false);
            $table->boolean('notify_warning')->default(false);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('notify_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notify_enabled')->default(false);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notify_deadline', 'notify_warning']);
        });
    }
};
