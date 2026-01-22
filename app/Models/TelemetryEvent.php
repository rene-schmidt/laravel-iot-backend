<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent model representing a single telemetry event.
 *
 * Each record stores metadata about the source/device
 * and an arbitrary telemetry payload (cast to an array).
 */
class TelemetryEvent extends Model
{
    /**
     * Attributes that are mass-assignable.
     *
     * This allows safe usage of TelemetryEvent::create()
     * with these specific fields.
     */
    protected $fillable = [
        'source',   // Origin of the telemetry data (e.g. gateway)
        'device',   // Device identifier
        'payload',  // Telemetry payload (stored as JSON)
    ];

    /**
     * Attribute casting configuration.
     *
     * The payload column is automatically converted
     * from JSON to an array when read, and back to JSON when written.
     */
    protected $casts = [
        'payload' => 'array',
    ];
}
