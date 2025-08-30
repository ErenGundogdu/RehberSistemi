<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BirimController;
use App\Http\Controllers\UnvanController;
use App\Http\Controllers\PersonelController;

Route::apiResource('birimler', BirimController::class);
Route::apiResource('unvanlar', UnvanController::class);
Route::apiResource('personel', PersonelController::class);
