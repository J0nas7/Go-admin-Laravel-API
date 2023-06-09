<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('/', function () { return view('welcome'); });
Route::get('/page', [PageController::class, 'showSpecificPage']);