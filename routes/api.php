<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['adminonly']], function () {
    /*
        Page stuff
    */
    // Get basic WP options
    Route::get('/basicPageInfo', [PageController::class, 'basicPageInfo']);
    // Get a menu location
    Route::get('/getMenuLocation/{termId}', [PageController::class, 'getMenuLocation']);

    /*
        WooCommerce Orders
    */
    // Read summary of all orders
    Route::post('/readAllOrdersSummary', [OrderController::class, 'readAllOrdersSummary']);
    Route::get('/readAllOrdersSummary', [OrderController::class, 'readAllOrdersSummary']);
    
    // Read one specific order
    Route::post('/readOneOrder', [OrderController::class, 'readOneOrder']);
});
Route::get('/readOneOrder', [OrderController::class, 'readOneOrder']);

/*
    WP Admin Authentication
*/
// Admin login
Route::post('/adminLogin', [AuthController::class, 'adminLogin']);
// Admin logout
Route::get('/adminLogout', [AuthController::class, 'adminLogout']);
// Check for admin login
Route::get('/adminLoggedInTest', [AuthController::class, 'adminLoggedInTest']);