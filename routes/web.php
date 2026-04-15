<?php

use App\Http\Controllers\AccountMasterController;
use App\Http\Controllers\BillPaymentController;
use App\Http\Controllers\BudgetPlanController;
use App\Http\Controllers\DailyExpenseController;
use App\Http\Controllers\ItemDepartureController;
use App\Http\Controllers\ItemMasterController;
use App\Http\Controllers\PartnerCollectionController;
use App\Http\Controllers\PartnerMasterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockEntryController;
use App\Http\Controllers\SupplierMasterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth.custom'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/item-master', [ItemMasterController::class, 'index'])->name('item-master.index');
Route::post('/item-master', [ItemMasterController::class, 'store'])->name('item-master.store');
Route::get('/item-master/{itemMaster}/edit', [ItemMasterController::class, 'edit'])->name('item-master.edit');
Route::put('/item-master/{itemMaster}', [ItemMasterController::class, 'update'])->name('item-master.update');
Route::delete('/item-master/{itemMaster}', [ItemMasterController::class, 'destroy'])->name('item-master.destroy');

Route::get('/supplier-master', [SupplierMasterController::class, 'index'])->name('supplier-master.index');
Route::post('/supplier-master', [SupplierMasterController::class, 'store'])->name('supplier-master.store');
Route::get('/supplier-master/{supplierMaster}/edit', [SupplierMasterController::class, 'edit'])->name('supplier-master.edit');
Route::put('/supplier-master/{supplierMaster}', [SupplierMasterController::class, 'update'])->name('supplier-master.update');
Route::delete('/supplier-master/{supplierMaster}', [SupplierMasterController::class, 'destroy'])->name('supplier-master.destroy');

Route::get('/account-masters', [AccountMasterController::class, 'index'])->name('account-masters.index');
Route::post('/account-masters', [AccountMasterController::class, 'store'])->name('account-masters.store');
Route::get('/account-masters/{account}/edit', [AccountMasterController::class, 'edit'])->name('account-masters.edit');
Route::put('/account-masters/{account}', [AccountMasterController::class, 'update'])->name('account-masters.update');
Route::delete('/account-masters/{account}', [AccountMasterController::class, 'destroy'])->name('account-masters.destroy');

// Debug route to test store
Route::post('/debug-store', function (Request $request) {
    return response()->json([
        'all' => $request->all(),
        'files' => $request->files->all(),
    ]);
});

Route::get('/partner-master', [PartnerMasterController::class, 'index'])->name('partner-master.index');
Route::post('/partner-master', [PartnerMasterController::class, 'store'])->name('partner-master.store');
Route::get('/partner-master/{partnerMaster}/edit', [PartnerMasterController::class, 'edit'])->name('partner-master.edit');
Route::put('/partner-master/{partnerMaster}', [PartnerMasterController::class, 'update'])->name('partner-master.update');
Route::delete('/partner-master/{partnerMaster}', [PartnerMasterController::class, 'destroy'])->name('partner-master.destroy');

Route::get('/daily-expenses', [DailyExpenseController::class, 'index'])->name('daily-expenses.index');
Route::post('/daily-expenses', [DailyExpenseController::class, 'store'])->name('daily-expenses.store');
Route::get('/daily-expenses/{dailyExpense}/edit', [DailyExpenseController::class, 'edit'])->name('daily-expenses.edit');
Route::put('/daily-expenses/{dailyExpense}', [DailyExpenseController::class, 'update'])->name('daily-expenses.update');
Route::delete('/daily-expenses/{dailyExpense}', [DailyExpenseController::class, 'destroy'])->name('daily-expenses.destroy');

Route::get('/bill-payments', [BillPaymentController::class, 'index'])->name('bill-payments.index');
Route::post('/bill-payments', [BillPaymentController::class, 'store'])->name('bill-payments.store');
Route::get('/bill-payments/{billPayment}/edit', [BillPaymentController::class, 'edit'])->name('bill-payments.edit');
Route::put('/bill-payments/{billPayment}', [BillPaymentController::class, 'update'])->name('bill-payments.update');
Route::delete('/bill-payments/{billPayment}', [BillPaymentController::class, 'destroy'])->name('bill-payments.destroy');

Route::get('/budget-plan', [BudgetPlanController::class, 'index'])->name('budget-plan.index');
Route::post('/budget-plan', [BudgetPlanController::class, 'store'])->name('budget-plan.store');
Route::get('/budget-plan/{budgetPlan}/edit', [BudgetPlanController::class, 'edit'])->name('budget-plan.edit');
Route::put('/budget-plan/{budgetPlan}', [BudgetPlanController::class, 'update'])->name('budget-plan.update');
Route::delete('/budget-plan/{budgetPlan}', [BudgetPlanController::class, 'destroy'])->name('budget-plan.destroy');

Route::get('/partner-collection', [PartnerCollectionController::class, 'index'])->name('partner-collection.index');
Route::post('/partner-collection', [PartnerCollectionController::class, 'store'])->name('partner-collection.store');
Route::get('/partner-collection/{partnerCollection}/edit', [PartnerCollectionController::class, 'edit'])->name('partner-collection.edit');
Route::put('/partner-collection/{partnerCollection}', [PartnerCollectionController::class, 'update'])->name('partner-collection.update');
Route::delete('/partner-collection/{partnerCollection}', [PartnerCollectionController::class, 'destroy'])->name('partner-collection.destroy');

Route::get('/stock-entry', [StockEntryController::class, 'index'])->name('stock-entry.index');
Route::post('/stock-entry', [StockEntryController::class, 'store'])->name('stock-entry.store');
Route::get('/stock-entry/{stockEntry}/edit', [StockEntryController::class, 'edit'])->name('stock-entry.edit');
Route::put('/stock-entry/{stockEntry}', [StockEntryController::class, 'update'])->name('stock-entry.update');
Route::delete('/stock-entry/{stockEntry}', [StockEntryController::class, 'destroy'])->name('stock-entry.destroy');

Route::get('/item-departures', [ItemDepartureController::class, 'index'])->name('item-departures.index');
Route::post('/item-departures', [ItemDepartureController::class, 'store'])->name('item-departures.store');
Route::get('/item-departures/{itemDeparture}/edit', [ItemDepartureController::class, 'edit'])->name('item-departures.edit');
Route::put('/item-departures/{itemDeparture}', [ItemDepartureController::class, 'update'])->name('item-departures.update');
Route::delete('/item-departures/{itemDeparture}', [ItemDepartureController::class, 'destroy'])->name('item-departures.destroy');
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
