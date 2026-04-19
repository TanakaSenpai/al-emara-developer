<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountLog;
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

            $partnerCollection = PartnerCollection::create([
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

            // Update account balance (add collection amount) and create log entry
            $account = Account::where('account_number', $request->account_master)->first();
            if ($account) {
                $newBalance = $account->balance_amount + $netAmount;
                $account->update(['balance_amount' => $newBalance]);

                // Create account log entry (credit - money coming in)
                AccountLog::create([
                    'transaction_date' => $request->date,
                    'account_id' => $account->id,
                    'transaction_type' => 'Partner Collection',
                    'description' => 'Collection from ' . $request->partners . ' (Net: ' . $netAmount . ', Paid: ' . $request->total_paid_amount . ')',
                    'debit_amount' => 0,
                    'credit_amount' => $netAmount,
                    'balance_after' => $newBalance,
                    'reference_id' => $partnerCollection->id,
                    'reference_type' => PartnerCollection::class,
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
            $oldNetAmount = $partnerCollection->net_amount;
            if ($oldPartner) {
                $oldPartner->update([
                    'paid_amount' => $oldPartner->paid_amount - $partnerCollection->total_paid_amount,
                    'due_amount' => $oldPartner->due_amount + $oldNetAmount,
                ]);
            }

            // Revert old account balance (subtract old collection amount)
            $oldAccount = Account::where('account_number', $partnerCollection->account_master)->first();
            if ($oldAccount) {
                $revertedBalance = $oldAccount->balance_amount - $oldNetAmount;
                $oldAccount->update(['balance_amount' => $revertedBalance]);

                // Create account log entry for the reversal
                AccountLog::create([
                    'transaction_date' => $request->date,
                    'account_id' => $oldAccount->id,
                    'transaction_type' => 'Partner Collection (Reversed)',
                    'description' => 'Reversal: Collection from ' . $partnerCollection->partners,
                    'debit_amount' => $oldNetAmount,
                    'credit_amount' => 0,
                    'balance_after' => $revertedBalance,
                    'reference_id' => $partnerCollection->id,
                    'reference_type' => PartnerCollection::class,
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

            // Update new account balance (add new collection amount) and create log entry
            $newAccount = Account::where('account_number', $request->account_master)->first();
            if ($newAccount) {
                $newBalance = $newAccount->balance_amount + $netAmount;
                $newAccount->update(['balance_amount' => $newBalance]);

                // Create account log entry
                AccountLog::create([
                    'transaction_date' => $request->date,
                    'account_id' => $newAccount->id,
                    'transaction_type' => 'Partner Collection (Update)',
                    'description' => 'Updated: Collection from ' . $request->partners . ' (Net: ' . $netAmount . ')',
                    'debit_amount' => 0,
                    'credit_amount' => $netAmount,
                    'balance_after' => $newBalance,
                    'reference_id' => $partnerCollection->id,
                    'reference_type' => PartnerCollection::class,
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
            $netAmount = $partnerCollection->net_amount;
            if ($partner) {
                $partner->update([
                    'paid_amount' => $partner->paid_amount - $partnerCollection->total_paid_amount,
                    'due_amount' => $partner->due_amount + $netAmount,
                ]);
            }

            // Revert account balance (subtract collection amount) and create log entry
            $account = Account::where('account_number', $partnerCollection->account_master)->first();
            if ($account) {
                $newBalance = $account->balance_amount - $netAmount;
                $account->update(['balance_amount' => $newBalance]);

                // Create account log entry for the reversal
                AccountLog::create([
                    'transaction_date' => now()->format('Y-m-d'),
                    'account_id' => $account->id,
                    'transaction_type' => 'Partner Collection (Deleted)',
                    'description' => 'Reversal: Collection from ' . $partnerCollection->partners,
                    'debit_amount' => $netAmount,
                    'credit_amount' => 0,
                    'balance_after' => $newBalance,
                    'reference_id' => null,
                    'reference_type' => null,
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
