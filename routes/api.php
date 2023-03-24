<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/basicPageInfo', [PageController::class, 'basicPageInfo']);
Route::get('/getMenuLocation/{termId}', [PageController::class, 'getMenuLocation']);

// Get a menu location
Route::get('/getMenuLocation', [PageController::class, 'getMenuLocation']);

// WP admin
Route::post('/adminLogin', [AuthController::class, 'adminLogin']);
Route::get('/adminLogout', [AuthController::class, 'adminLogout']);
Route::get('/adminLoggedInTest', [AuthController::class, 'adminLoggedInTest']);