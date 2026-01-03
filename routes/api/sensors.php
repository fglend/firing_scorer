<?php

use App\Http\Controllers\Api\IotReadingController;
use Illuminate\Support\Facades\Route;

Route::post('/iot-readings', [IotReadingController::class, 'store']);
