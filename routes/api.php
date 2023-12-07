<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;


Route::post('/count-sundays', [HomeController::class, 'countSundays']);
