<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountMasterController extends Controller
{
    public function index()
    {
        try {
            $accounts = Account::orderBy('id', 'desc')->get();
        } catch (\Exception $e) {
            Log::error('Error loading accounts: '.$e->getMessage());
            $accounts = collect();
            session()->now('error', 'Database Error: '.$e->getMessage());
        }

        return view('admin.account-masters', compact('accounts'));
    }

    public function store(Request $request)
    {
        Log::info('Store request received', $request->all());

        $validated = $request->validate([
            'account_number' => 'required|string|max:255|unique:account_masters,account_number',
            'description' => 'required|string|max:255',
            'balance_amount' => 'required|numeric|min:0',
        ]);

        Log::info('Validated data', $validated);

        try {
            $account = Account::create($validated);
            Log::info('Account created', ['id' => $account->id]);
        } catch (\Exception $e) {
            Log::error('Error creating account: '.$e->getMessage());

            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('account-masters.index')->with('success', 'Account created successfully!');
    }

    public function edit(Account $account)
    {
        return view('admin.account-masters', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'account_number' => 'required|string|max:255|unique:account_masters,account_number,'.$account->id,
            'description' => 'required|string|max:255',
            'balance_amount' => 'required|numeric|min:0',
        ]);

        try {
            $account->update($validated);
            Log::info('Account updated', ['id' => $account->id]);

            return redirect()->route('account-masters.index')->with('success', 'Account updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating account: '.$e->getMessage());

            return back()->with('error', 'Error updating account: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(Account $account)
    {
        try {
            $account->delete();
            Log::info('Account deleted', ['id' => $account->id]);

            return redirect()->route('account-masters.index')->with('success', 'Account deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting account: '.$e->getMessage());

            return back()->with('error', 'Error deleting account: '.$e->getMessage());
        }
    }
}
