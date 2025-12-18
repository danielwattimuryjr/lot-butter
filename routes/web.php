<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            $exceptShow = ['except' => ['show']];

            Route::resource('teams', TeamController::class, $exceptShow);
            Route::resource('employees', EmployeeController::class, $exceptShow);
            Route::resource('accounts', AccountController::class, [
                'parameters' => ['accounts' => 'user'],
                ...$exceptShow
            ]);
        });
});

require __DIR__ . '/auth.php';
