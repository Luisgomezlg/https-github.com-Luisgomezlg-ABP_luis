<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CiudadesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/users', [UsersController::class, "index"]);

    Route::get('/info-city', function () {
    return Inertia::render('gestion/Ciudades');
    })->name('info-city');

    Route::get('/mis-ciudades', function () {
    return Inertia::render('gestion/CiudadesList');
    })->name('mis-ciudades');

    Route::get('/ciudades/{id}', [CiudadesController::class, "ciudades"]);
    Route::post('/ciudades_save', [CiudadesController::class, "ciudades_save"]);
    Route::post('/ciudades_delete/{id}', [CiudadesController::class, "ciudades_delete"]);
    
});
