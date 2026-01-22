<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TelemetryEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * API controller responsible for ingesting telemetry data
 * coming from gateways or devices.
 *
 * The endpoint performs:
 * - API key authentication via request header
 * - Minimal, flexible payload validation
 * - Persistent storage of telemetry events
 * - JSON acknowledgement with the created event ID
 */
class TelemetryIngestController extends Controller
{
    /**
     * Store a telemetry event sent by a gateway or device.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // -------------------------------------------------
        // API KEY AUTHENTICATION
        // -------------------------------------------------
        // Expected API key is read from configuration or environment
        $expected = (string) config('iot.api_key', env('IOT_API_KEY', ''));

        // API key provided by the client via HTTP header
        $given = (string) $request->header('X-API-KEY', '');

        // Reject request if key is missing or does not match
        if ($expected === '' || !hash_equals($expected, $given)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        // -------------------------------------------------
        // REQUEST VALIDATION (minimal and flexible)
        // -------------------------------------------------
        // Payload structure is intentionally kept generic
        // to support different devices and message formats
        $validated = $request->validate([
            'source'  => ['nullable', 'string', 'max:100'], // Origin of the data (e.g. gateway)
            'device'  => ['nullable', 'string', 'max:100'], // Device identifier
            'payload' => ['required', 'array'],             // Telemetry payload
        ]);

        // Ensure payload is not empty
        if (empty($validated['payload'])) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Empty payload',
            ], 422);
        }

        // -------------------------------------------------
        // PAYLOAD NORMALIZATION
        // -------------------------------------------------
        // Add a server-side timestamp if none was provided
        if (!isset($validated['payload']['ts'])) {
            $validated['payload']['ts'] = now()->toIso8601String();
        }

        // -------------------------------------------------
        // PERSIST TELEMETRY EVENT
        // -------------------------------------------------
        // Store the telemetry event in the database
        $event = TelemetryEvent::create([
            'source'  => $validated['source'] ?? 'gateway',
            'device'  => $validated['device'] ?? null,
            'payload' => $validated['payload'],
        ]);

        // -------------------------------------------------
        // RESPONSE
        // -------------------------------------------------
        // Return success status and the created event ID
        return response()->json([
            'status' => 'ok',
            'id'     => $event->id,
        ], 201);
    }
}
