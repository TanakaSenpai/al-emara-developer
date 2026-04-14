<?php

use App\Http\Controllers\AccountMasterController;
use App\Http\Controllers\DailyExpenseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/', function () {
    return view('admin.dashboard');
});

Route::get('/item-master', function () {
    return view('admin.item-master');
});

Route::get('/supplier-master', function () {
    return view('admin.supplier-master');
});

Route::get('/account-masters', [AccountMasterController::class, 'index'])->name('account-masters.index');
Route::post('/account-masters', [AccountMasterController::class, 'store'])->name('account-masters.store');

// Debug route to test store
Route::post('/debug-store', function (Request $request) {
    return response()->json([
        'all' => $request->all(),
        'files' => $request->files->all(),
    ]);
});

Route::get('/partner-master', function () {
    return view('admin.partner-master');
});

Route::get('/daily-expenses', [DailyExpenseController::class, 'index'])->name('daily-expenses.index');
Route::post('/daily-expenses', [DailyExpenseController::class, 'store'])->name('daily-expenses.store');

Route::get('/bill-payments', function () {
    return view('admin.bill-payments');
});

Route::get('/budget-plan', function () {
    return view('admin.budget-plan');
});

Route::get('/partner-collection', function () {
    return view('admin.partner-collection');
});

Route::get('/stock-entry', function () {
    return view('admin.stock-entry');
});

Route::get('/item-departures', function () {
    return view('admin.item-departures');
});

// Temporary route to create storage symlink on cPanel
Route::get('/init-storage', function () {
    Artisan::call('storage:link');

    return 'Storage link created!';
});

// Temporary route to clear caches
Route::get('/clear-cache', function () {
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');

    return 'All caches cleared!';
});
