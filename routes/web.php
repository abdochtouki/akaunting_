<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Http\Controllers\Common\Dashboards;
/**
 * 'web' middleware applied to all routes
 *
 * @see \App\Providers\Route::mapWebRoutes
 */
Route::get('/1', 'Common\Dashboards@show')->name('dashboard');
Livewire::setScriptRoute(function ($handle) {
    $base = request()->getBasePath();

    return Route::get($base . '/vendor/livewire/livewire/dist/livewire.min.js', $handle);
});
