<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/generate_send_otp', [App\Http\Controllers\OtpController::class, 'make_otp']); //add service catagory record to the table
Route::get('/auth_otp/{send_id}/{otp}', [App\Http\Controllers\OtpController::class, 'auth_otp']); //add service catagory record to the table
