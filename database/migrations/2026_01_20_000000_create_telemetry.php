<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for creating the telemetry_events table.
 *
 * This table stores incoming telemetry data together with
 * basic metadata such as source and device identifiers.
 */
return new class extends Migration {

    /**
     * Run the migrations.
     *
     * Creates the telemetry_events table with fields for
     * source, device, and a flexible JSON payload.
     */
    public function up(): void
    {
        Schema::create('telemetry_events', function (Blueprint $table) {
            $table->id();                         // Primary key

            $table->string('source')->nullable(); // Source of the data (e.g. "raspi2-gateway")
            $table->string('device')->nullable(); // Optional device identifier or name

            $table->json('payload');              // Full telemetry payload stored as JSON

            $table->timestamps();                 // created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the telemetry_events table.
     */
    public function down(): void
    {
        Schema::dropIfExists('telemetry_events');
    }
};
