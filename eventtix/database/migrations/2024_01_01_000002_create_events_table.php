<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug', 191)->unique();
            $table->text('description');
            $table->string('category', 191)->default('other');
            $table->string('location');
            $table->text('venue_details')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('cover_image')->nullable();
            $table->unsignedInteger('capacity')->default(0);
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('draft');
            $table->timestamps();

            $table->index(['status', 'start_date']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
