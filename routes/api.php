<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TelemetryIngestController;
use App\Http\Controllers\Api\TelemetryQueryController;
use App\Http\Controllers\Api\CdcCommandController;

/*
|--------------------------------------------------------------------------
| Telemetry ingest (Raspberry Pi → Laravel)
|--------------------------------------------------------------------------
| Receives structured JSON data from the gateway (API key protected)
*/
Route::post('/telemetry/ingest', [TelemetryIngestController::class, 'store'])
    ->name('api.telemetry.ingest');

/*
|--------------------------------------------------------------------------
| Telemetry query (Laravel UI → API)
|--------------------------------------------------------------------------
| Returns the latest telemetry payload for dashboard polling
*/
Route::get('/telemetry/latest', [TelemetryQueryController::class, 'latest'])
    ->name('api.telemetry.latest');

/*
|--------------------------------------------------------------------------
| USB CDC command endpoint (optional / stub)
|--------------------------------------------------------------------------
| UI → Laravel → Raspberry Pi → STM32 (serial)
| Not implemented yet, but route exists to avoid Blade errors
*/
Route::post('/cdc/command', [CdcCommandController::class, 'send'])
    ->name('api.cdc.command');
