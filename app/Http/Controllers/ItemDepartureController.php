<?php

namespace App\Http\Controllers;

use App\Models\ItemDeparture;
use App\Models\ItemMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ItemDepartureController extends Controller
{
    public function index()
    {
        try {
            $departures = ItemDeparture::orderBy('date', 'desc')->orderBy('id', 'desc')->get();
            $items = ItemMaster::all();
        } catch (\Exception $e) {
            Log::error('Error loading item departures: '.$e->getMessage());
            $departures = collect();
            $items = collect();
            session()->now('error', 'Database Error: '.$e->getMessage());
        }

        return view('admin.item-departures', compact('departures', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'item_master' => 'required|string|max:255',
            'departure_qnty' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            // Get current stock for the item
            $item = ItemMaster::where('item_name', $request->item_master)->first();
            $prevStock = $item ? $item->stock_balance : 0;
            $balanceQnty = $prevStock - $request->departure_qnty;

            // Ensure balance doesn't go negative
            if ($balanceQnty < 0) {
                return back()->with('error', 'Departure quantity cannot exceed available stock.')->withInput();
            }

            // Create departure record
            ItemDeparture::create([
                'date' => $request->date,
                'item_master' => $request->item_master,
                'prev_stock' => $prevStock,
                'departure_qnty' => $request->departure_qnty,
                'balance_qnty' => $balanceQnty,
                'notes' => $request->notes,
            ]);

            // Update item stock
            if ($item) {
                $item->update(['stock_balance' => $balanceQnty]);
            }

            return redirect()->route('item-departures.index')->with('success', 'Item departure recorded successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating item departure: '.$e->getMessage());

            return back()->with('error', 'Error saving departure: '.$e->getMessage())->withInput();
        }
    }

    public function edit(ItemDeparture $itemDeparture)
    {
        $items = ItemMaster::all();
        return view('admin.item-departures', compact('itemDeparture', 'items'));
    }

    public function update(Request $request, ItemDeparture $itemDeparture)
    {
        $request->validate([
            'date' => 'required|date',
            'item_master' => 'required|string|max:255',
            'departure_qnty' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            // Return old departure quantity to stock first
            $oldItem = ItemMaster::where('item_name', $itemDeparture->item_master)->first();
            if ($oldItem) {
                $oldItem->update(['stock_balance' => $oldItem->stock_balance + $itemDeparture->departure_qnty]);
            }

            // Get current stock for the new item
            $newItem = ItemMaster::where('item_name', $request->item_master)->first();
            $prevStock = $newItem ? $newItem->stock_balance : 0;
            $balanceQnty = $prevStock - $request->departure_qnty;

            // Ensure balance doesn't go negative
            if ($balanceQnty < 0) {
                // Revert the old item stock change if validation fails
                if ($oldItem) {
                    $oldItem->update(['stock_balance' => $oldItem->stock_balance - $itemDeparture->departure_qnty]);
                }
                return back()->with('error', 'Departure quantity cannot exceed available stock.')->withInput();
            }

            // Update departure record
            $itemDeparture->update([
                'date' => $request->date,
                'item_master' => $request->item_master,
                'prev_stock' => $prevStock,
                'departure_qnty' => $request->departure_qnty,
                'balance_qnty' => $balanceQnty,
                'notes' => $request->notes,
            ]);

            // Update new item stock
            if ($newItem) {
                $newItem->update(['stock_balance' => $balanceQnty]);
            }

            return redirect()->route('item-departures.index')->with('success', 'Item departure updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating item departure: '.$e->getMessage());

            return back()->with('error', 'Error updating departure: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(ItemDeparture $itemDeparture)
    {
        try {
            // Return departure quantity to stock before deleting
            $item = ItemMaster::where('item_name', $itemDeparture->item_master)->first();
            if ($item) {
                $item->update(['stock_balance' => $item->stock_balance + $itemDeparture->departure_qnty]);
            }

            $itemDeparture->delete();

            return redirect()->route('item-departures.index')->with('success', 'Item departure deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting item departure: '.$e->getMessage());

            return back()->with('error', 'Error deleting departure: '.$e->getMessage());
        }
    }
}
