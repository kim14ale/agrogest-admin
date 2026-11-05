<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');

    Route::resource('breeds', Admin\BreedController::class)->except('show');
    Route::resource('stages', Admin\FeedingStageController::class)->except('show')
        ->parameters(['stages' => 'stage']);
    Route::resource('feeds', Admin\FeedController::class)->except('show');
    Route::resource('lots', Admin\LotController::class)->except('show');
    Route::resource('medicines', Admin\MedicineController::class)->except('show');
    Route::resource('animals', Admin\AnimalController::class)->except('show');
    Route::resource('weighings', Admin\WeighingController::class)->except('show');

    // Dropdown dependiente Lote → Animales (responde JSON).
    Route::get('lots/{lot}/animals', [Admin\WeighingController::class, 'animalsByLot'])->name('lots.animals');

    Route::get('parameters', [Admin\ParameterController::class, 'index'])->name('parameters.index');
    Route::put('parameters', [Admin\ParameterController::class, 'update'])->name('parameters.update');
});
