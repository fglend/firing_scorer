<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IotReading;
use App\Models\ShootingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IotReadingController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => ['nullable', 'string', 'max:100'],
            'session_id' => ['required', 'integer', 'exists:shooting_sessions,id'],
            'captured_at' => ['nullable', 'date'],
            'distance_m' => ['nullable', 'numeric', 'min:0', 'max:200'],
            'temperature_c' => ['nullable', 'numeric', 'min:-20', 'max:80'],
            'humidity_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'light_lux' => ['nullable', 'numeric', 'min:0', 'max:200000'],
            'imu_json' => ['nullable', 'array'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $session = ShootingSession::findOrFail($request->input('session_id'));

        $reading = IotReading::create([
            'shooting_session_id' => $session->id,
            'device_id' => $request->input('device_id'),
            'captured_at' => $request->input('captured_at'),
            'distance_m' => $request->input('distance_m'),
            'temperature_c' => $request->input('temperature_c'),
            'humidity_percent' => $request->input('humidity_percent'),
            'light_lux' => $request->input('light_lux'),
            'imu_json' => $request->input('imu_json'),
            'raw_payload' => $request->all(),
        ]);

        return response()->json([
            'ok' => true,
            'iot_reading_id' => $reading->id,
            'session_id' => $session->id,
        ]);
    }
}
