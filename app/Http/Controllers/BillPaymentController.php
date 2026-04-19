<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountLog;
use App\Models\BillPayment;
use App\Models\SupplierMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BillPaymentController extends Controller
{
    public function index()
    {
        try {
            $payments = BillPayment::orderBy('date', 'desc')->orderBy('id', 'desc')->get();
            $suppliers = SupplierMaster::all();
            $accounts = Account::all();
        } catch (\Exception $e) {
            Log::error('Error loading bill payments: '.$e->getMessage());
            $payments = collect();
            $suppliers = collect();
            $accounts = collect();
            session()->now('error', 'Database Error: '.$e->getMessage());
        }

        return view('admin.bill-payments', compact('payments', 'suppliers', 'accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'supplier_name' => 'required|string|max:255',
            'account_master' => 'required|string|max:255',
            'paid_amount' => 'required|numeric|min:0',
            'paid_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            // Get supplier's current due balance for last_balance
            $supplier = SupplierMaster::where('supplier_name', $request->supplier_name)->first();
            $lastBalance = $supplier ? $supplier->due_balance : 0;

            $billPayment = BillPayment::create([
                'date' => $request->date,
                'supplier_name' => $request->supplier_name,
                'account_master' => $request->account_master,
                'last_balance' => $lastBalance,
                'paid_amount' => $request->paid_amount,
                'paid_by' => $request->paid_by,
                'notes' => $request->notes,
            ]);

            // Update supplier's due balance (subtract paid amount)
            if ($supplier) {
                $supplier->update(['due_balance' => $lastBalance - $request->paid_amount]);
            }

            // Update account balance (deduct paid amount) and create log entry
            $account = Account::where('account_number', $request->account_master)->first();
            if ($account) {
                $newBalance = $account->balance_amount - $request->paid_amount;
                $account->update(['balance_amount' => $newBalance]);

                // Create account log entry
                AccountLog::create([
                    'transaction_date' => $request->date,
                    'account_id' => $account->id,
                    'transaction_type' => 'Bill Payment',
                    'description' => 'Payment to ' . $request->supplier_name . ($request->paid_by ? ' by ' . $request->paid_by : ''),
                    'debit_amount' => $request->paid_amount,
                    'credit_amount' => 0,
                    'balance_after' => $newBalance,
                    'reference_id' => $billPayment->id,
                    'reference_type' => BillPayment::class,
                ]);
            }

            return redirect()->route('bill-payments.index')->with('success', 'Bill payment recorded successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating bill payment: '.$e->getMessage());

            return back()->with('error', 'Error saving payment: '.$e->getMessage())->withInput();
        }
    }

    public function edit(BillPayment $billPayment)
    {
        $suppliers = SupplierMaster::all();
        $accounts = Account::all();
        return view('admin.bill-payments', compact('billPayment', 'suppliers', 'accounts'));
    }

    public function update(Request $request, BillPayment $billPayment)
    {
        $request->validate([
            'date' => 'required|date',
            'supplier_name' => 'required|string|max:255',
            'account_master' => 'required|string|max:255',
            'paid_amount' => 'required|numeric|min:0',
            'paid_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            // Revert old supplier's due balance (add back the old paid amount)
            $oldSupplier = SupplierMaster::where('supplier_name', $billPayment->supplier_name)->first();
            if ($oldSupplier) {
                $oldSupplier->update(['due_balance' => $oldSupplier->due_balance + $billPayment->paid_amount]);
            }

            // Revert old account balance (add back old paid amount)
            $oldAccount = Account::where('account_number', $billPayment->account_master)->first();
            if ($oldAccount) {
                $revertedBalance = $oldAccount->balance_amount + $billPayment->paid_amount;
                $oldAccount->update(['balance_amount' => $revertedBalance]);

                // Create account log entry for the reversal
                AccountLog::create([
                    'transaction_date' => $request->date,
                    'account_id' => $oldAccount->id,
                    'transaction_type' => 'Bill Payment (Reversed)',
                    'description' => 'Reversal: Payment to ' . $billPayment->supplier_name,
                    'debit_amount' => 0,
                    'credit_amount' => $billPayment->paid_amount,
                    'balance_after' => $revertedBalance,
                    'reference_id' => $billPayment->id,
                    'reference_type' => BillPayment::class,
                ]);
            }

            // Get new supplier's current due balance
            $newSupplier = SupplierMaster::where('supplier_name', $request->supplier_name)->first();
            $lastBalance = $newSupplier ? $newSupplier->due_balance : 0;

            // Update payment record
            $billPayment->update([
                'date' => $request->date,
                'supplier_name' => $request->supplier_name,
                'account_master' => $request->account_master,
                'last_balance' => $lastBalance,
                'paid_amount' => $request->paid_amount,
                'paid_by' => $request->paid_by,
                'notes' => $request->notes,
            ]);

            // Update new supplier's due balance (subtract new paid amount)
            if ($newSupplier) {
                $newSupplier->update(['due_balance' => $lastBalance - $request->paid_amount]);
            }

            // Update new account balance (deduct new paid amount) and create log entry
            $newAccount = Account::where('account_number', $request->account_master)->first();
            if ($newAccount) {
                $newBalance = $newAccount->balance_amount - $request->paid_amount;
                $newAccount->update(['balance_amount' => $newBalance]);

                // Create account log entry
                AccountLog::create([
                    'transaction_date' => $request->date,
                    'account_id' => $newAccount->id,
                    'transaction_type' => 'Bill Payment (Update)',
                    'description' => 'Updated: Payment to ' . $request->supplier_name . ($request->paid_by ? ' by ' . $request->paid_by : ''),
                    'debit_amount' => $request->paid_amount,
                    'credit_amount' => 0,
                    'balance_after' => $newBalance,
                    'reference_id' => $billPayment->id,
                    'reference_type' => BillPayment::class,
                ]);
            }

            return redirect()->route('bill-payments.index')->with('success', 'Bill payment updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating bill payment: '.$e->getMessage());

            return back()->with('error', 'Error updating payment: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(BillPayment $billPayment)
    {
        try {
            // Return paid amount to supplier's due balance
            $supplier = SupplierMaster::where('supplier_name', $billPayment->supplier_name)->first();
            if ($supplier) {
                $supplier->update(['due_balance' => $supplier->due_balance + $billPayment->paid_amount]);
            }

            // Restore account balance (add back paid amount) and create log entry
            $account = Account::where('account_number', $billPayment->account_master)->first();
            if ($account) {
                $newBalance = $account->balance_amount + $billPayment->paid_amount;
                $account->update(['balance_amount' => $newBalance]);

                // Create account log entry for the refund
                AccountLog::create([
                    'transaction_date' => now()->format('Y-m-d'),
                    'account_id' => $account->id,
                    'transaction_type' => 'Bill Payment (Deleted)',
                    'description' => 'Refund: Payment to ' . $billPayment->supplier_name,
                    'debit_amount' => 0,
                    'credit_amount' => $billPayment->paid_amount,
                    'balance_after' => $newBalance,
                    'reference_id' => null,
                    'reference_type' => null,
                ]);
            }

            $billPayment->delete();

            return redirect()->route('bill-payments.index')->with('success', 'Bill payment deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting bill payment: '.$e->getMessage());

            return back()->with('error', 'Error deleting payment: '.$e->getMessage());
        }
    }
}
