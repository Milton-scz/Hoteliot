<?php

use App\Http\Controllers\DeviceApiController;
use Illuminate\Support\Facades\Route;


Route::post('/api/device/block', DeviceApiController::class)->name('api.device.block');
