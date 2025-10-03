<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\KeywordController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutas de tareas y palabras claves
Route::get('/tasks', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::patch('/tasks/{id}/toggle', [TaskController::class, 'toggle']);
Route::get('/keywords', [KeywordController::class, 'index']);
Route::post('/keywords', [KeywordController::class, 'store']);

