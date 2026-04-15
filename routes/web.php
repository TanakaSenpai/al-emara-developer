<?php

use App\Http\Controllers\AccountMasterController;
use App\Http\Controllers\BillPaymentController;
use App\Http\Controllers\BudgetPlanController;
use App\Http\Controllers\DailyExpenseController;
use App\Http\Controllers\ItemDepartureController;
use App\Http\Controllers\ItemMasterController;
use App\Http\Controllers\PartnerCollectionController;
use App\Http\Controllers\PartnerMasterController;
use App\Http\Controllers\StockEntryController;
use App\Http\Controllers\SupplierMasterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/', function () {
    return view('admin.dashboard');
});

Route::get('/item-master', [ItemMasterController::class, 'index'])->name('item-master.index');
Route::post('/item-master', [ItemMasterController::class, 'store'])->name('item-master.store');

Route::get('/supplier-master', [SupplierMasterController::class, 'index'])->name('supplier-master.index');
Route::post('/supplier-master', [SupplierMasterController::class, 'store'])->name('supplier-master.store');

Route::get('/account-masters', [AccountMasterController::class, 'index'])->name('account-masters.index');
Route::post('/account-masters', [AccountMasterController::class, 'store'])->name('account-masters.store');

// Debug route to test store
Route::post('/debug-store', function (Request $request) {
    return response()->json([
        'all' => $request->all(),
        'files' => $request->files->all(),
    ]);
});

Route::get('/partner-master', [PartnerMasterController::class, 'index'])->name('partner-master.index');
Route::post('/partner-master', [PartnerMasterController::class, 'store'])->name('partner-master.store');

Route::get('/daily-expenses', [DailyExpenseController::class, 'index'])->name('daily-expenses.index');
Route::post('/daily-expenses', [DailyExpenseController::class, 'store'])->name('daily-expenses.store');
Route::get('/daily-expenses/{dailyExpense}/edit', [DailyExpenseController::class, 'edit'])->name('daily-expenses.edit');
Route::put('/daily-expenses/{dailyExpense}', [DailyExpenseController::class, 'update'])->name('daily-expenses.update');
Route::delete('/daily-expenses/{dailyExpense}', [DailyExpenseController::class, 'destroy'])->name('daily-expenses.destroy');

Route::get('/bill-payments', [BillPaymentController::class, 'index'])->name('bill-payments.index');
Route::post('/bill-payments', [BillPaymentController::class, 'store'])->name('bill-payments.store');

Route::get('/budget-plan', [BudgetPlanController::class, 'index'])->name('budget-plan.index');
Route::post('/budget-plan', [BudgetPlanController::class, 'store'])->name('budget-plan.store');

Route::get('/partner-collection', [PartnerCollectionController::class, 'index'])->name('partner-collection.index');
Route::post('/partner-collection', [PartnerCollectionController::class, 'store'])->name('partner-collection.store');

Route::get('/stock-entry', [StockEntryController::class, 'index'])->name('stock-entry.index');
Route::post('/stock-entry', [StockEntryController::class, 'store'])->name('stock-entry.store');
Route::get('/stock-entry/{stockEntry}/edit', [StockEntryController::class, 'edit'])->name('stock-entry.edit');
Route::put('/stock-entry/{stockEntry}', [StockEntryController::class, 'update'])->name('stock-entry.update');
Route::delete('/stock-entry/{stockEntry}', [StockEntryController::class, 'destroy'])->name('stock-entry.destroy');

Route::get('/item-departures', [ItemDepartureController::class, 'index'])->name('item-departures.index');
Route::post('/item-departures', [ItemDepartureController::class, 'store'])->name('item-departures.store');

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
