<?php

use App\Http\Controllers\Api\InitiatePaymentController;
use App\Http\Controllers\Api\RegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::post('/register', [RegistrationController::class, 'register']);
    Route::get('/verify/{account}', [RegistrationController::class, 'checkIfTenantExists']);
    Route::post('/create-session', [InitiatePaymentController::class, 'createSession']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
