<?php

use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');
Route::fallback(fn () => redirect()->route('home')->setStatusCode(301));
