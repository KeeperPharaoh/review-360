<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('event_id')->nullable();
            $table->unsignedBigInteger('question_id')->nullable();
            $table->unsignedBigInteger('assignment_id')->nullable();

            $table->text('answer');

            $table->json('log')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('set null');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('set null');
            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
