<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained();
            $table->foreignId('status_id')->default(1)->constrained();
            $table->string('title');
            $table->dateTime('due_date');
            $table->unsignedSmallInteger('estimated_minutes')->nullable();
            $table->unsignedTinyInteger('progress')->default(0);
            $table->string('image_path')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
