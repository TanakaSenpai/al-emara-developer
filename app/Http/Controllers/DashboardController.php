<?php

namespace App\Http\Controllers;

use App\Models\DailyExpense;
use App\Models\BillPayment;
use App\Models\ItemMaster;
use App\Models\StockEntry;
use App\Models\PartnerMaster;
use App\Models\SupplierMaster;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Financial Summary
            $totalExpenses = DailyExpense::sum('expense_amount') ?? 0;
            $totalBillPaid = BillPayment::sum('paid_amount') ?? 0;
            $totalStockValue = StockEntry::sum('sub_total') ?? 0;
            $totalPartnerDue = PartnerMaster::sum('due_amount') ?? 0;

            // All items with stock (sorted by stock balance, top 5 for cards)
            $itemsWithStock = ItemMaster::where('stock_balance', '>', 0)
                ->orderBy('stock_balance', 'desc')
                ->get();

            // Top 4 items by stock for the card display
            $topItems = $itemsWithStock->take(4);

            // Recent activity (last 5 entries)
            $recentExpenses = DailyExpense::latest()->take(5)->get();
            $recentBillPayments = BillPayment::latest()->take(5)->get();

            return view('admin.dashboard', compact(
                'totalExpenses',
                'totalBillPaid',
                'totalStockValue',
                'totalPartnerDue',
                'itemsWithStock',
                'topItems',
                'recentExpenses',
                'recentBillPayments'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading dashboard data: ' . $e->getMessage());

            return view('admin.dashboard', [
                'totalExpenses' => 0,
                'totalBillPaid' => 0,
                'totalStockValue' => 0,
                'totalPartnerDue' => 0,
                'itemsWithStock' => collect(),
                'topItems' => collect(),
                'recentExpenses' => collect(),
                'recentBillPayments' => collect(),
                'error' => 'Error loading dashboard data: ' . $e->getMessage()
            ]);
        }
    }
}
