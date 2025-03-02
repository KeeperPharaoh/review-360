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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('review_method_id')->nullable();

            $table->string('name');
            $table->datetime('start_at');
            $table->datetime('end_at')->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('review_method_id')->references('id')->on('review_methods')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
