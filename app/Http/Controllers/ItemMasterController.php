<?php

namespace App\Http\Controllers;

use App\Models\ItemMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ItemMasterController extends Controller
{
    public function index()
    {
        try {
            $items = ItemMaster::orderBy('item_name')->get();
        } catch (\Exception $e) {
            Log::error('Error loading items: '.$e->getMessage());
            $items = collect();
            session()->now('error', 'Database Error: '.$e->getMessage());
        }

        return view('admin.item-master', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255|unique:item_masters,item_name',
            'purchase_rate' => 'required|numeric|min:0',
            'stock_balance' => 'required|numeric|min:0',
        ]);

        try {
            ItemMaster::create($validated);

            return redirect()->route('item-master.index')->with('success', 'Item created successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating item: '.$e->getMessage());

            return back()->with('error', 'Error saving item: '.$e->getMessage())->withInput();
        }
    }

    public function edit(ItemMaster $itemMaster)
    {
        return view('admin.item-master', compact('itemMaster'));
    }

    public function update(Request $request, ItemMaster $itemMaster)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255|unique:item_masters,item_name,'.$itemMaster->id,
            'purchase_rate' => 'required|numeric|min:0',
            'stock_balance' => 'required|numeric|min:0',
        ]);

        try {
            $itemMaster->update($validated);

            return redirect()->route('item-master.index')->with('success', 'Item updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating item: '.$e->getMessage());

            return back()->with('error', 'Error updating item: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(ItemMaster $itemMaster)
    {
        try {
            $itemMaster->delete();

            return redirect()->route('item-master.index')->with('success', 'Item deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting item: '.$e->getMessage());

            return back()->with('error', 'Error deleting item: '.$e->getMessage());
        }
    }
}
