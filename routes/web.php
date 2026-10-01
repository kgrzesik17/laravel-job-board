<?php

use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => to_route('jobs.index'));  // to_route() = redirect()->route()

Route::resource('jobs', JobController::class)
->only(['index', 'show']);
