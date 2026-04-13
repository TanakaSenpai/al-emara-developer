<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\DailyExpense;
use Illuminate\Http\Request;

class DailyExpenseController extends Controller
{
    public function index()
    {
        $expenses = DailyExpense::with('account')->orderBy('expense_date', 'desc')->orderBy('id', 'desc')->get();
        $accounts = Account::all();

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
            'account_id' => 'nullable|exists:accounts,id',
        ]);

        DailyExpense::create($validated);

        return redirect()->route('daily-expenses.index')->with('success', 'Expense added successfully!');
    }
}
