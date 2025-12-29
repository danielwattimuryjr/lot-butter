<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BOMController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\LogisticController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

$exceptShow = ['except' => ['show']];

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth'])->group(function () use ($exceptShow) {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::get('export', ExportController::class)->name('export');

    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () use ($exceptShow) {
            Route::resource('teams', TeamController::class, $exceptShow);
            Route::resource('employees', EmployeeController::class, $exceptShow);
            Route::resource('accounts', AccountController::class, [
                'parameters' => ['accounts' => 'user'],
                ...$exceptShow
            ]);
        });

    Route::middleware('role:employee')
        ->prefix('employee')
        ->name('employee.')
        ->group(function () use ($exceptShow) {
            Route::prefix('production')
                ->name('production.')
                ->group(function () use ($exceptShow) {
                    Route::resource('products', ProductController::class, $exceptShow);
                    Route::resource('components', ComponentController::class, $exceptShow);
                    Route::resource('products.bill-of-materials', BOMController::class, $exceptShow)
                        ->parameters(['bill-of-materials' => 'component']);
                });

            Route::prefix('finance')
                ->name('finance.')
                ->group(function () use ($exceptShow) {
                    Route::resource('incomes', IncomeController::class, $exceptShow);
                    Route::get('journals', JournalController::class)->name('journals.index');
                });

            Route::prefix('supply-chain')
                ->name('supply-chain.')
                ->group(function () use ($exceptShow) {
                    Route::resource('logistics', LogisticController::class, $exceptShow);
                    Route::resource(
                        'procurements',
                        ProcurementController::class,
                        [
                            'parameters' => ['procurements' => 'purchase'],
                            ...$exceptShow
                        ]
                    );
                });
        });
});

require __DIR__ . '/auth.php';
