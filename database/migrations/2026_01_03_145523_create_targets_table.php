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
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shooting_session_id')
                ->constrained()
                ->cascadeOnDelete()
                ->nullable();

            $table->string('target_type')->nullable(); // e.g. M16 standard target
            $table->integer('ring_10_radius')->nullable();
            $table->integer('ring_9_radius')->nullable();
            $table->integer('ring_8_radius')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('targets');
    }
};
