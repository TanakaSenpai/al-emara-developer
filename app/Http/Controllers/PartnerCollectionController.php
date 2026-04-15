<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\BudgetPlan;
use App\Models\PartnerCollection;
use App\Models\PartnerMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PartnerCollectionController extends Controller
{
    public function index()
    {
        try {
            $collections = PartnerCollection::orderBy('date', 'desc')->orderBy('id', 'desc')->get();
            $partners = PartnerMaster::all();
            $budgetPlans = BudgetPlan::all();
            $accounts = Account::all();
        } catch (\Exception $e) {
            Log::error('Error loading partner collections: '.$e->getMessage());
            $collections = collect();
            $partners = collect();
            $budgetPlans = collect();
            $accounts = collect();
            session()->now('error', 'Database Error: '.$e->getMessage());
        }

        return view('admin.partner-collection', compact('collections', 'partners', 'budgetPlans', 'accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'partners' => 'required|string|max:255',
            'budget_plan' => 'nullable|string|max:255',
            'account_master' => 'required|string|max:255',
            'total_paid_amount' => 'required|numeric|min:0',
            'extra_charges' => 'nullable|numeric|min:0',
            'net_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            // Get partner's current due balance
            $partner = PartnerMaster::where('partner_name', $request->partners)->first();
            $totalDueBalance = $partner ? $partner->due_amount : 0;

            // Calculate net amount (paid - extra charges)
            $netAmount = $request->net_amount ?? ($request->total_paid_amount - ($request->extra_charges ?? 0));

            PartnerCollection::create([
                'date' => $request->date,
                'partners' => $request->partners,
                'budget_plan' => $request->budget_plan,
                'account_master' => $request->account_master,
                'total_due_balance' => $totalDueBalance,
                'total_paid_amount' => $request->total_paid_amount,
                'extra_charges' => $request->extra_charges ?? 0,
                'net_amount' => $netAmount,
                'notes' => $request->notes,
            ]);

            // Update partner's paid amount and due amount
            if ($partner) {
                $partner->update([
                    'paid_amount' => $partner->paid_amount + $request->total_paid_amount,
                    'due_amount' => $partner->due_amount - $netAmount,
                ]);
            }

            return redirect()->route('partner-collection.index')->with('success', 'Partner collection recorded successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating partner collection: '.$e->getMessage());

            return back()->with('error', 'Error saving collection: '.$e->getMessage())->withInput();
        }
    }

    public function edit(PartnerCollection $partnerCollection)
    {
        $partners = PartnerMaster::all();
        $budgetPlans = BudgetPlan::all();
        $accounts = Account::all();
        return view('admin.partner-collection', compact('partnerCollection', 'partners', 'budgetPlans', 'accounts'));
    }

    public function update(Request $request, PartnerCollection $partnerCollection)
    {
        $request->validate([
            'date' => 'required|date',
            'partners' => 'required|string|max:255',
            'budget_plan' => 'nullable|string|max:255',
            'account_master' => 'required|string|max:255',
            'total_paid_amount' => 'required|numeric|min:0',
            'extra_charges' => 'nullable|numeric|min:0',
            'net_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            // Revert old partner's balances
            $oldPartner = PartnerMaster::where('partner_name', $partnerCollection->partners)->first();
            if ($oldPartner) {
                $oldNetAmount = $partnerCollection->net_amount;
                $oldPartner->update([
                    'paid_amount' => $oldPartner->paid_amount - $partnerCollection->total_paid_amount,
                    'due_amount' => $oldPartner->due_amount + $oldNetAmount,
                ]);
            }

            // Get new partner's current due balance
            $newPartner = PartnerMaster::where('partner_name', $request->partners)->first();
            $totalDueBalance = $newPartner ? $newPartner->due_amount : 0;

            // Calculate new net amount
            $netAmount = $request->net_amount ?? ($request->total_paid_amount - ($request->extra_charges ?? 0));

            // Update collection record
            $partnerCollection->update([
                'date' => $request->date,
                'partners' => $request->partners,
                'budget_plan' => $request->budget_plan,
                'account_master' => $request->account_master,
                'total_due_balance' => $totalDueBalance,
                'total_paid_amount' => $request->total_paid_amount,
                'extra_charges' => $request->extra_charges ?? 0,
                'net_amount' => $netAmount,
                'notes' => $request->notes,
            ]);

            // Update new partner's balances
            if ($newPartner) {
                $newPartner->update([
                    'paid_amount' => $newPartner->paid_amount + $request->total_paid_amount,
                    'due_amount' => $newPartner->due_amount - $netAmount,
                ]);
            }

            return redirect()->route('partner-collection.index')->with('success', 'Partner collection updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating partner collection: '.$e->getMessage());

            return back()->with('error', 'Error updating collection: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(PartnerCollection $partnerCollection)
    {
        try {
            // Revert partner's balances
            $partner = PartnerMaster::where('partner_name', $partnerCollection->partners)->first();
            if ($partner) {
                $partner->update([
                    'paid_amount' => $partner->paid_amount - $partnerCollection->total_paid_amount,
                    'due_amount' => $partner->due_amount + $partnerCollection->net_amount,
                ]);
            }

            $partnerCollection->delete();

            return redirect()->route('partner-collection.index')->with('success', 'Partner collection deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting partner collection: '.$e->getMessage());

            return back()->with('error', 'Error deleting collection: '.$e->getMessage());
        }
    }
}
