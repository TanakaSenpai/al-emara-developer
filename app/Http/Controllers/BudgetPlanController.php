<?php

namespace App\Http\Controllers;

use App\Models\BudgetPlan;
use App\Models\PartnerMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BudgetPlanController extends Controller
{
    public function index()
    {
        try {
            $budgetPlans = BudgetPlan::orderBy('date', 'desc')->orderBy('id', 'desc')->get();
            $partners = PartnerMaster::all();
        } catch (\Exception $e) {
            Log::error('Error loading budget plans: '.$e->getMessage());
            $budgetPlans = collect();
            $partners = collect();
            session()->now('error', 'Database Error: '.$e->getMessage());
        }

        return view('admin.budget-plan', compact('budgetPlans', 'partners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'partner_master' => 'required|string|max:255',
            'budget_details' => 'required|string|max:1000',
            'charge_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            BudgetPlan::create($request->validated());

            // Update partner's total_charges and due_amount
            $partner = PartnerMaster::where('partner_name', $request->partner_master)->first();
            if ($partner) {
                $partner->update([
                    'total_charges' => $partner->total_charges + $request->charge_amount,
                    'due_amount' => $partner->due_amount + $request->charge_amount,
                ]);
            }

            return redirect()->route('budget-plan.index')->with('success', 'Budget plan created successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating budget plan: '.$e->getMessage());

            return back()->with('error', 'Error saving budget plan: '.$e->getMessage())->withInput();
        }
    }
}
