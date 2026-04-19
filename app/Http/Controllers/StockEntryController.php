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

            // Update supplier's due balance (increase - now owes this amount)
            $supplier = SupplierMaster::where('supplier_name', $request->supplier_master)->first();
            if ($supplier) {
                $supplier->update([
                    'due_balance' => $supplier->due_balance + $request->sub_total
                ]);
            }

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

    public function edit(StockEntry $stockEntry)
    {
        $stockEntry->load('details');
        $suppliers = SupplierMaster::all();
        $items = ItemMaster::all();

        return view('admin.stock-entry', compact('stockEntry', 'suppliers', 'items'));
    }

    public function update(Request $request, StockEntry $stockEntry)
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
            // Revert old supplier's due balance (subtract old sub_total)
            $oldSupplier = SupplierMaster::where('supplier_name', $stockEntry->supplier_master)->first();
            if ($oldSupplier) {
                $oldSupplier->update([
                    'due_balance' => $oldSupplier->due_balance - $stockEntry->sub_total
                ]);
            }

            // Revert old stock changes first
            foreach ($stockEntry->details as $detail) {
                $item = ItemMaster::where('item_name', $detail->item_master)->first();
                if ($item) {
                    $item->update(['stock_balance' => $item->stock_balance - $detail->qnty]);
                }
            }

            // Delete old details
            $stockEntry->details()->delete();

            // Update stock entry
            $stockEntry->update([
                'supplier_master' => $request->supplier_master,
                'date' => $request->date,
                'date_of_party_chalan' => $request->date_of_party_chalan,
                'ref_party_chalan' => $request->ref_party_chalan,
                'sub_total' => $request->sub_total,
                'notes' => $request->notes,
            ]);

            // Create new stock entry details with updated stock
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

            // Update new supplier's due balance (increase - now owes this amount)
            $newSupplier = SupplierMaster::where('supplier_name', $request->supplier_master)->first();
            if ($newSupplier) {
                $newSupplier->update([
                    'due_balance' => $newSupplier->due_balance + $request->sub_total
                ]);
            }

            return redirect()->route('stock-entry.index')->with('success', 'Stock entry updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating stock entry: '.$e->getMessage());

            return back()->with('error', 'Error updating stock entry: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(StockEntry $stockEntry)
    {
        try {
            // Revert supplier's due balance (subtract the sub_total - debt cleared)
            $supplier = SupplierMaster::where('supplier_name', $stockEntry->supplier_master)->first();
            if ($supplier) {
                $supplier->update([
                    'due_balance' => $supplier->due_balance - $stockEntry->sub_total
                ]);
            }

            // Revert stock changes before deleting
            foreach ($stockEntry->details as $detail) {
                $item = ItemMaster::where('item_name', $detail->item_master)->first();
                if ($item) {
                    $item->update(['stock_balance' => $item->stock_balance - $detail->qnty]);
                }
            }

            // Delete details and parent
            $stockEntry->details()->delete();
            $stockEntry->delete();

            return redirect()->route('stock-entry.index')->with('success', 'Stock entry deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting stock entry: '.$e->getMessage());

            return back()->with('error', 'Error deleting stock entry: '.$e->getMessage());
        }
    }
}
