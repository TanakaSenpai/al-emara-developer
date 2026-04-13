<?php

use App\Http\Controllers\DailyExpenseController;
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

Route::get('/account-masters', function () {
    return view('admin.account-masters');
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
