<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Course\Models\Course;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->text('attachment_url')->nullable();
            $table->string('attachment_name')->nullable();
            $table->text('video_url')->nullable();
            $table->string('video_name')->nullable();
            $table->unsignedInteger('duration')->nullable()->comment('duration in minutes');
            $table->boolean('is_published')->default(false);
            $table->foreignIdFor(Course::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->index(['course_id', 'order']);
            $table->index(['course_id', 'is_published']);
            $table->unique(['course_id' , 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};