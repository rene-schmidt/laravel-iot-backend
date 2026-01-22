<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Handles CDC command requests sent via the API.
 *
 * This controller validates incoming commands and immediately returns
 * a JSON response confirming successful acceptance of the command.
 */
class CdcCommandController extends Controller
{
    /**
     * Receive and process a CDC command.
     *
     * The method validates the request payload and responds with a simple
     * acknowledgement containing the submitted command.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {
        // Validate the incoming request data.
        // The "command" field is required, must be a string,
        // and is limited to 200 characters.
        $data = $request->validate([
            'command' => ['required', 'string', 'max:200'],
        ]);

        // Return a JSON response confirming that the command
        // has been received and accepted successfully.
        return response()->json([
            'status'  => 'ok',                       // Indicates successful processing
            'message' => 'CDC command accepted',     // Human-readable confirmation
            'command' => $data['command'],           // Echoes the received command
        ]);
    }
}
