<?php

namespace App\Http\Controllers;

use App\Models\ItemMaster;
use App\Models\StockEntry;
use App\Models\StockEntryDetail;
use App\Models\SupplierMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StockEntryController extends Controller
{
    public function index()
    {
        try {
            $stockEntries = StockEntry::with('details')->orderBy('date', 'desc')->orderBy('id', 'desc')->get();
            $suppliers = SupplierMaster::all();
            $items = ItemMaster::all();
        } catch (\Exception $e) {
            Log::error('Error loading stock entries: '.$e->getMessage());
            $stockEntries = collect();
            $suppliers = collect();
            $items = collect();
            session()->now('error', 'Database Error: '.$e->getMessage());
        }

        return view('admin.stock-entry', compact('stockEntries', 'suppliers', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_master' => 'required|string|max:255',
            'date' => 'required|date',
            'date_of_party_chalan' => 'nullable|date',
            'ref_party_chalan' => 'nullable|string|max:255',
            'item_master' => 'required|array|min:1',
            'item_master.*' => 'required|string|max:255',
            'qnty' => 'required|array|min:1',
            'qnty.*' => 'required|numeric|min:0',
            'purchase_rate' => 'required|array|min:1',
            'purchase_rate.*' => 'required|numeric|min:0',
            'sub_total' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            // Create stock entry
            $stockEntry = StockEntry::create([
                'supplier_master' => $request->supplier_master,
                'date' => $request->date,
                'date_of_party_chalan' => $request->date_of_party_chalan,
                'ref_party_chalan' => $request->ref_party_chalan,
                'sub_total' => $request->sub_total,
                'notes' => $request->notes,
            ]);

            // Create stock entry details
            foreach ($request->item_master as $index => $itemMaster) {
                $qnty = $request->qnty[$index];
                $purchaseRate = $request->purchase_rate[$index];
                $totalPrice = $qnty * $purchaseRate;

                // Get current stock for the item
                $item = ItemMaster::where('item_name', $itemMaster)->first();
                $oldStock = $item ? $item->stock_balance : 0;
                $newStock = $oldStock + $qnty;

                StockEntryDetail::create([
                    'stock_entry_id' => $stockEntry->id,
                    'item_master' => $itemMaster,
                    'old_stock' => $oldStock,
                    'qnty' => $qnty,
                    'new_stock' => $newStock,
                    'purchase_rate' => $purchaseRate,
                    'total_price' => $totalPrice,
                ]);

                // Update item stock
                if ($item) {
                    $item->update(['stock_balance' => $newStock]);
                }
            }

            return redirect()->route('stock-entry.index')->with('success', 'Stock entry added successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating stock entry: '.$e->getMessage());

            return back()->with('error', 'Error saving stock entry: '.$e->getMessage())->withInput();
        }
    }
}
