<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BreweryController;

Route::post('/spa.login', function (Request $request) {

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {

        $user = Auth::user();

        $existingToken = $user->tokens()->where('name', 'spa_token')->first();

        if ($existingToken) {

            $existingToken->delete();

            $token = $user->createToken('spa_token', ['server:read'])->plainTextToken;

             return response()->json([
                'message' => 'Old token was invalid, a new token has been created.',
                'token' => $token
            ]);

        } 

        else {
            
            $token = $user->createToken('spa_token', ['server:read'])->plainTextToken;

            return response()->json([
                'token' => $token
            ]);
        }

    }

    return response()->json([
        'message' => 'Invalid credentials'
    ], 401);

});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/breweries', [BreweryController::class, 'index']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});