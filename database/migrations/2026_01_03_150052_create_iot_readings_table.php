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
        Schema::create('iot_readings', function (Blueprint $table) {
            $table->id();
            // Relationship: one shooting session has many iot_readings
            $table->foreignId('shooting_session_id')
                ->constrained()
                ->cascadeOnDelete();

            // Device metadata
            $table->string('device_id')->nullable(); // e.g., ESP32-01, ESP32-CAM-Front

            // Sensor parameters (nullable because not all sensors are required)
            $table->decimal('distance_m', 8, 3)->nullable();         // e.g., 25.000 meters
            $table->decimal('temperature_c', 5, 2)->nullable();      // e.g., 29.50 C
            $table->decimal('humidity_percent', 5, 2)->nullable();   // e.g., 70.25 %
            $table->decimal('light_lux', 10, 2)->nullable();         // e.g., 350.00 lux

            // Optional: store IMU values as JSON (accel/gyro)
            $table->json('imu_json')->nullable();

            // For auditing/debug: store original payload sent by device
            $table->json('raw_payload')->nullable();

            // Device-side time (preferred) + Laravel timestamps
            $table->dateTime('captured_at')->nullable();
            $table->timestamps();

            $table->index(['shooting_session_id', 'captured_at']);
            $table->index(['device_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iot_readings');
    }
};
