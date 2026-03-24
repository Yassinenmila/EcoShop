<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;

Route::get('/test',function(){
    return response()->json([
        'message'=>'api ok'
    ]);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile', function(Request $request) {
        return response()->json($request->user());
    });
});
