<?php

use App\Http\Controllers\CalculationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware('auth')->group(function(){

    Route::get('/calculator', [CalculationController::class, 'create'])->name('calculator.create');
    Route::post('/calculate', [CalculationController::class, 'store'])->name('calculate.store');
    Route::delete('/calculation/{calculation}', [CalculationController::class, 'destroy']);
    Route::get('/calculation/delete_all', [CalculationController::class, 'destroyAll']);
    Route::get('/api/calculation/history', [CalculationController::class, 'history'])->name('history');
});

Route::get('dashboard', function () {
    return redirect('/calculator');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
