<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AbonneController;
use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\Api\LivreController;
use App\Http\Controllers\Api\EmpruntController;
use App\Http\Controllers\Api\LogController;


Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);

// endpoints publics (si tu veux restreindre, mets-les dans auth:sanctum)
Route::get('/livres', [LivreController::class,'index']);
Route::get('/livres/{id}', [LivreController::class,'show']);
Route::get('/categories', [CategorieController::class,'index']);
Route::get('/abonnes', [AbonneController::class,'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class,'logout']);
    Route::get('/user', [AuthController::class,'me']);

    //Route::apiResource('abonnes', AbonneController::class)->except(['index']); // index public déjà accessible
    //Route::apiResource('categories', CategorieController::class);
    //Route::apiResource('livres', LivreController::class)->except(['index','show']);
    //Route::apiResource('emprunts', EmpruntController::class);
    //Route::get('/logs', [LogController::class,'index']);
});

// Public routes - no authentication required
Route::post('/login', [AuthController::class, 'login']);

// Protected routes - require authentication via Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);
    
    // Add your other protected API routes here
    Route::get('/emprunts', [EmpruntController::class, 'index']);
    Route::post('/emprunts', [EmpruntController::class, 'store']);
    Route::get('/emprunts/{id}', [EmpruntController::class, 'show']);
    Route::put('/emprunts/{id}', [EmpruntController::class, 'update']);
    Route::delete('/emprunts/{id}', [EmpruntController::class, 'destroy']);
    });