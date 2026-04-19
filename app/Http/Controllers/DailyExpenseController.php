<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountLog;
use App\Models\DailyExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DailyExpenseController extends Controller
{
    public function index()
    {
        try {
            $expenses = DailyExpense::with('account')->orderBy('expense_date', 'desc')->orderBy('id', 'desc')->get();
            $accounts = Account::all();
        } catch (\Exception $e) {
            Log::error('Error loading expenses: '.$e->getMessage());
            $expenses = collect();
            $accounts = collect();
            session()->now('error', 'Database Error: '.$e->getMessage());
        }

        return view('admin.daily-expenses', compact('expenses', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_date' => 'required|date',
            'expense_details' => 'required|string|max:255',
            'expense_amount' => 'required|numeric|min:0',
            'expense_by' => 'nullable|string|max:255',
            'voucher_no' => 'nullable|string|max:255',
            'account_id' => 'nullable|exists:account_masters,id',
        ]);

        try {
            $dailyExpense = DailyExpense::create($validated);

            // Update account balance if account is selected
            if ($validated['account_id']) {
                $account = Account::find($validated['account_id']);
                if ($account) {
                    $newBalance = $account->balance_amount - $validated['expense_amount'];
                    $account->update([
                        'balance_amount' => $newBalance
                    ]);

                    // Create account log entry
                    AccountLog::create([
                        'transaction_date' => $validated['expense_date'],
                        'account_id' => $account->id,
                        'transaction_type' => 'Daily Expense',
                        'description' => $validated['expense_details'] . ($validated['expense_by'] ? ' by ' . $validated['expense_by'] : ''),
                        'debit_amount' => $validated['expense_amount'],
                        'credit_amount' => 0,
                        'balance_after' => $newBalance,
                        'reference_id' => $dailyExpense->id,
                        'reference_type' => DailyExpense::class,
                    ]);
                }
            }

            return redirect()->route('daily-expenses.index')->with('success', 'Expense added successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating daily expense: '.$e->getMessage());

            return back()->with('error', 'Error saving expense: '.$e->getMessage())->withInput();
        }
    }

    public function edit(DailyExpense $dailyExpense)
    {
        $accounts = Account::all();
        return view('admin.daily-expenses', compact('dailyExpense', 'accounts'));
    }

    public function update(Request $request, DailyExpense $dailyExpense)
    {
        $validated = $request->validate([
            'expense_date' => 'required|date',
            'expense_details' => 'required|string|max:255',
            'expense_amount' => 'required|numeric|min:0',
            'expense_by' => 'nullable|string|max:255',
            'voucher_no' => 'nullable|string|max:255',
            'account_id' => 'nullable|exists:account_masters,id',
        ]);

        try {
            $oldAccountId = $dailyExpense->account_id;
            $oldExpenseAmount = $dailyExpense->expense_amount;

            // Revert old account balance if different account or amount changed
            if ($oldAccountId && ($oldAccountId != $validated['account_id'] || $oldExpenseAmount != $validated['expense_amount'])) {
                $oldAccount = Account::find($oldAccountId);
                if ($oldAccount) {
                    $oldAccount->update([
                        'balance_amount' => $oldAccount->balance_amount + $oldExpenseAmount
                    ]);
                }
            }

            $dailyExpense->update($validated);

            // Update new account balance and create log entries
            if ($validated['account_id']) {
                $newAccount = Account::find($validated['account_id']);
                if ($newAccount) {
                    // If same account and only amount changed, we already reverted above, so just subtract new amount
                    if ($oldAccountId == $validated['account_id'] && $oldExpenseAmount != $validated['expense_amount']) {
                        $newBalance = $newAccount->balance_amount - $validated['expense_amount'];
                        $newAccount->update([
                            'balance_amount' => $newBalance
                        ]);

                        // Create account log entry for the new expense amount
                        AccountLog::create([
                            'transaction_date' => $validated['expense_date'],
                            'account_id' => $newAccount->id,
                            'transaction_type' => 'Daily Expense (Update)',
                            'description' => 'Updated: ' . $validated['expense_details'] . ($validated['expense_by'] ? ' by ' . $validated['expense_by'] : ''),
                            'debit_amount' => $validated['expense_amount'],
                            'credit_amount' => 0,
                            'balance_after' => $newBalance,
                            'reference_id' => $dailyExpense->id,
                            'reference_type' => DailyExpense::class,
                        ]);
                    } elseif ($oldAccountId != $validated['account_id']) {
                        // Different account - subtract from new account
                        $newBalance = $newAccount->balance_amount - $validated['expense_amount'];
                        $newAccount->update([
                            'balance_amount' => $newBalance
                        ]);

                        // Create account log entry for the new account
                        AccountLog::create([
                            'transaction_date' => $validated['expense_date'],
                            'account_id' => $newAccount->id,
                            'transaction_type' => 'Daily Expense (Transfer)',
                            'description' => 'Transferred: ' . $validated['expense_details'] . ($validated['expense_by'] ? ' by ' . $validated['expense_by'] : ''),
                            'debit_amount' => $validated['expense_amount'],
                            'credit_amount' => 0,
                            'balance_after' => $newBalance,
                            'reference_id' => $dailyExpense->id,
                            'reference_type' => DailyExpense::class,
                        ]);
                    }
                }
            }

            return redirect()->route('daily-expenses.index')->with('success', 'Expense updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating daily expense: '.$e->getMessage());

            return back()->with('error', 'Error updating expense: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(DailyExpense $dailyExpense)
    {
        try {
            // Restore account balance before deleting
            if ($dailyExpense->account_id) {
                $account = Account::find($dailyExpense->account_id);
                if ($account) {
                    $newBalance = $account->balance_amount + $dailyExpense->expense_amount;
                    $account->update([
                        'balance_amount' => $newBalance
                    ]);

                    // Create account log entry for the refund
                    AccountLog::create([
                        'transaction_date' => now()->format('Y-m-d'),
                        'account_id' => $account->id,
                        'transaction_type' => 'Daily Expense (Deleted)',
                        'description' => 'Refunded: ' . $dailyExpense->expense_details . ($dailyExpense->expense_by ? ' by ' . $dailyExpense->expense_by : ''),
                        'debit_amount' => 0,
                        'credit_amount' => $dailyExpense->expense_amount,
                        'balance_after' => $newBalance,
                        'reference_id' => null,
                        'reference_type' => null,
                    ]);
                }
            }

            $dailyExpense->delete();

            return redirect()->route('daily-expenses.index')->with('success', 'Expense deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting daily expense: '.$e->getMessage());

            return back()->with('error', 'Error deleting expense: '.$e->getMessage());
        }
    }
}
