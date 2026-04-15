<?php

namespace App\Http\Controllers;

use App\Models\Account;
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
            DailyExpense::create($validated);

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
            $dailyExpense->update($validated);

            return redirect()->route('daily-expenses.index')->with('success', 'Expense updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating daily expense: '.$e->getMessage());

            return back()->with('error', 'Error updating expense: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(DailyExpense $dailyExpense)
    {
        try {
            $dailyExpense->delete();

            return redirect()->route('daily-expenses.index')->with('success', 'Expense deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting daily expense: '.$e->getMessage());

            return back()->with('error', 'Error deleting expense: '.$e->getMessage());
        }
    }
}
