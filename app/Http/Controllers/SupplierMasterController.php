<?php

namespace App\Http\Controllers;

use App\Models\SupplierMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SupplierMasterController extends Controller
{
    public function index()
    {
        try {
            $suppliers = SupplierMaster::orderBy('supplier_name')->get();
        } catch (\Exception $e) {
            Log::error('Error loading suppliers: '.$e->getMessage());
            $suppliers = collect();
            session()->now('error', 'Database Error: '.$e->getMessage());
        }

        return view('admin.supplier-master', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required|string|max:255|unique:supplier_masters,supplier_name',
            'mobile' => 'required|string|max:50',
            'address' => 'nullable|string|max:1000',
            'due_balance' => 'nullable|numeric|min:0',
        ]);

        try {
            SupplierMaster::create([
                'supplier_name' => $request->supplier_name,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'due_balance' => $request->due_balance ?? 0,
            ]);

            return redirect()->route('supplier-master.index')->with('success', 'Supplier created successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating supplier: '.$e->getMessage());

            return back()->with('error', 'Error saving supplier: '.$e->getMessage())->withInput();
        }
    }

    public function edit(SupplierMaster $supplierMaster)
    {
        return view('admin.supplier-master', compact('supplierMaster'));
    }

    public function update(Request $request, SupplierMaster $supplierMaster)
    {
        $request->validate([
            'supplier_name' => 'required|string|max:255|unique:supplier_masters,supplier_name,'.$supplierMaster->id,
            'mobile' => 'required|string|max:50',
            'address' => 'nullable|string|max:1000',
            'due_balance' => 'nullable|numeric|min:0',
        ]);

        try {
            $supplierMaster->update([
                'supplier_name' => $request->supplier_name,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'due_balance' => $request->due_balance ?? 0,
            ]);

            return redirect()->route('supplier-master.index')->with('success', 'Supplier updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating supplier: '.$e->getMessage());

            return back()->with('error', 'Error updating supplier: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(SupplierMaster $supplierMaster)
    {
        try {
            $supplierMaster->delete();

            return redirect()->route('supplier-master.index')->with('success', 'Supplier deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting supplier: '.$e->getMessage());

            return back()->with('error', 'Error deleting supplier: '.$e->getMessage());
        }
    }
}
