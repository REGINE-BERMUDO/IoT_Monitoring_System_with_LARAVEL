<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorReading;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SensorReadingController extends Controller
{
    public function store(Request $request)
    {
        $expectedToken = (string) config('services.iot.device_token', '');
        $providedToken = (string) $request->bearerToken();

        if ($expectedToken === '' || ! hash_equals($expectedToken, $providedToken)) {
            return response()->json([
                'message' => 'Unauthorized device.',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'device_id'   => 'required|string|max:50',
            'temperature' => 'required|numeric|between:-40,85',
            'humidity'    => 'required|numeric|between:0,100',
            'recorded_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // The ESP32 sends UTC; convert to the app timezone before saving
        if (! empty($data['recorded_at'])) {
            $data['recorded_at'] = Carbon::parse($data['recorded_at'])
                ->setTimezone(config('app.timezone'));
        }

        $reading = SensorReading::create($data);

        return response()->json([
            'message' => 'Sensor reading saved successfully.',
            'data'    => $reading,
        ], 201);
    }
    public function index()
    {
    return response()->json(
        SensorReading::latest('id')->limit(20)->get()
        );
    }
}