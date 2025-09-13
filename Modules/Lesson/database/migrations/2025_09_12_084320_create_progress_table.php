<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Lesson\Enums\LessonProgressStatus;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("progress", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->cascadeOnDelete();
            $table->foreignId("lesson_id")->constrained()->cascadeOnDelete();
            $table->boolean("is_completed")->default(false);
            $table->integer("percentage")->default(0);
            $table->timestamp("started_at")->nullable();
            $table->timestamp("completed_at")->nullable();
            $table->integer("time_spent")->default(0);
            $table
                ->tinyInteger("status")
                ->default(LessonProgressStatus::NOT_STARTED->value);
            $table->timestamp("last_accessed_at")->nullable();
            $table->timestamps();
            $table->unique(["user_id", "lesson_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("lesson_progress");
    }
};
