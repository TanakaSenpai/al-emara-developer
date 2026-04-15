<?php

namespace App\Http\Controllers;

use App\Models\PartnerMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PartnerMasterController extends Controller
{
    public function index()
    {
        $error = null;
        try {
            $partners = PartnerMaster::orderBy('partner_name')->get();
        } catch (\Exception $e) {
            Log::error('Error loading partners: '.$e->getMessage());
            $partners = collect();
            $error = 'Database Error: '.$e->getMessage();
        }

        return view('admin.partner-master', compact('partners', 'error'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'partner_name' => 'required|string|max:255|unique:partner_masters,partner_name',
            'mobile' => 'required|string|max:50',
            'address' => 'nullable|string|max:1000',
            'total_charges' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'due_amount' => 'nullable|numeric|min:0',
            'extra_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            PartnerMaster::create([
                'partner_name' => $request->partner_name,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'total_charges' => $request->total_charges ?? 0,
                'paid_amount' => $request->paid_amount ?? 0,
                'due_amount' => $request->due_amount ?? 0,
                'extra_amount' => $request->extra_amount ?? 0,
                'notes' => $request->notes,
            ]);

            return redirect()->route('partner-master.index')->with('success', 'Partner created successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating partner: '.$e->getMessage());

            return back()->with('error', 'Error saving partner: '.$e->getMessage())->withInput();
        }
    }
}
