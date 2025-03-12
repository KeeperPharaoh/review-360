<?php

use App\Http\Controllers\OneToOneController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/one-to-one', [OneToOneController::class, 'generateQuestions']);
Route::post('/user-meta', [OneToOneController::class, 'createUserMeta']);
Route::post('/answer', [OneToOneController::class, 'answer']);
