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

    public function edit(BudgetPlan $budgetPlan)
    {
        $partners = PartnerMaster::all();
        return view('admin.budget-plan', compact('budgetPlan', 'partners'));
    }

    public function update(Request $request, BudgetPlan $budgetPlan)
    {
        $request->validate([
            'date' => 'required|date',
            'partner_master' => 'required|string|max:255',
            'budget_details' => 'required|string|max:1000',
            'charge_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            // Revert old partner's charges and due amount
            $oldPartner = PartnerMaster::where('partner_name', $budgetPlan->partner_master)->first();
            if ($oldPartner) {
                $oldPartner->update([
                    'total_charges' => $oldPartner->total_charges - $budgetPlan->charge_amount,
                    'due_amount' => $oldPartner->due_amount - $budgetPlan->charge_amount,
                ]);
            }

            // Update budget plan
            $budgetPlan->update($request->validated());

            // Add new charges to new partner
            $newPartner = PartnerMaster::where('partner_name', $request->partner_master)->first();
            if ($newPartner) {
                $newPartner->update([
                    'total_charges' => $newPartner->total_charges + $request->charge_amount,
                    'due_amount' => $newPartner->due_amount + $request->charge_amount,
                ]);
            }

            return redirect()->route('budget-plan.index')->with('success', 'Budget plan updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating budget plan: '.$e->getMessage());

            return back()->with('error', 'Error updating budget plan: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(BudgetPlan $budgetPlan)
    {
        try {
            // Revert partner's charges and due amount
            $partner = PartnerMaster::where('partner_name', $budgetPlan->partner_master)->first();
            if ($partner) {
                $partner->update([
                    'total_charges' => $partner->total_charges - $budgetPlan->charge_amount,
                    'due_amount' => $partner->due_amount - $budgetPlan->charge_amount,
                ]);
            }

            $budgetPlan->delete();

            return redirect()->route('budget-plan.index')->with('success', 'Budget plan deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting budget plan: '.$e->getMessage());

            return back()->with('error', 'Error deleting budget plan: '.$e->getMessage());
        }
    }
}
