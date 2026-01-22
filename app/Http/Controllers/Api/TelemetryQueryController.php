<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TelemetryEvent;

/**
 * API controller for querying telemetry data.
 *
 * This controller provides read-only access to stored telemetry events,
 * optimized for frontend polling and UI consumption.
 */
class TelemetryQueryController extends Controller
{
    /**
     * Return the most recent telemetry event.
     *
     * If no telemetry data exists yet, a UI-friendly default response
     * with empty/null values is returned to keep the frontend stable.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function latest()
    {
        // Fetch the most recent telemetry event from the database
        $event = TelemetryEvent::query()->latest()->first();

        // If no event exists yet, return a predictable empty structure
        if (!$event) {
            // UI-friendly empty response to avoid null checks on the frontend
            return response()->json([
                'status' => 'ok',
                'data' => [
                    'ts'  => now()->toIso8601String(), // Current server timestamp
                    'i2c' => null,                     // No I2C data available
                    'can' => [],                       // No CAN frames available
                    'cdc' => null,                     // No CDC output available
                ],
            ]);
        }

        // Return telemetry payload merged with basic metadata
        return response()->json([
            'status' => 'ok',
            'data' => array_merge(
                [
                    'id'          => $event->id,                                // Database event ID
                    'source'      => $event->source,                            // Data source (e.g. gateway)
                    'device'      => $event->device,                            // Device identifier
                    'received_at' => $event->created_at?->toIso8601String(),    // Ingest timestamp
                ],
                // Merge stored payload if it is a valid array
                is_array($event->payload) ? $event->payload : []
            ),
        ]);
    }
}
