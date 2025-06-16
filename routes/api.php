<?php

use App\Http\Controllers\AddressController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::get('/test', function () {
    return response()->json(['message' => 'It works!']);
});
Route::prefix('address')->group(function () {
    Route::get('/provinces', [AddressController::class, 'getProvinces']);
    Route::get('/districts', [AddressController::class, 'getDistricts']);
    Route::get('/wards', [AddressController::class, 'getWards']);
});
