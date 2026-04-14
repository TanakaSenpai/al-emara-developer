<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountMasterController extends Controller
{
    public function index()
    {
        $accounts = Account::orderBy('id', 'desc')->get();

        return view('admin.account-masters', compact('accounts'));
    }

    public function store(Request $request)
    {
        Log::info('Store request received', $request->all());

        $validated = $request->validate([
            'account_number' => 'required|string|max:255|unique:accounts,account_number',
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
}
