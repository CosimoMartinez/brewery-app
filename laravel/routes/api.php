<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BreweryController;

Route::post('/spa.login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Cerca un token esistente con il nome 'sanctum_spa_token'
        $existingToken = $user->tokens()->where('name', 'spa_token')->first();

        if ($existingToken) {
            // Restituisci il token esistente
            $token = $existingToken->plainTextToken;
        } else {
            // Crea un nuovo token se non esiste
            $token = $user->createToken('spa_token')->plainTextToken;
        }

        return response()->json([
            'token' => $token
        ]);
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