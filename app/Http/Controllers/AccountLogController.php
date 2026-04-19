<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountLog;
use App\Models\DailyExpense;
use App\Models\BillPayment;
use App\Models\PartnerCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountLogController extends Controller
{
    public function index(Request $request)
    {
        try {
            $accounts = Account::all();
            
            $query = AccountLog::with('account')->orderBy('transaction_date', 'desc')->orderBy('id', 'desc');
            
            // Filter by account if selected
            if ($request->filled('account_id')) {
                $query->where('account_id', $request->account_id);
            }
            
            // Filter by date range
            if ($request->filled('from_date')) {
                $query->where('transaction_date', '>=', $request->from_date);
            }
            if ($request->filled('to_date')) {
                $query->where('transaction_date', '<=', $request->to_date);
            }
            
            // Filter by transaction type
            if ($request->filled('transaction_type')) {
                $query->where('transaction_type', $request->transaction_type);
            }
            
            $logs = $query->paginate(50);
            
            // Calculate summary statistics
            $totalDebit = $query->sum('debit_amount');
            $totalCredit = $query->sum('credit_amount');
            
            // Get distinct transaction types for filter dropdown
            $transactionTypes = AccountLog::distinct()->pluck('transaction_type')->filter();
            
        } catch (\Exception $e) {
            Log::error('Error loading account logs: '.$e->getMessage());
            $logs = collect();
            $accounts = collect();
            $totalDebit = 0;
            $totalCredit = 0;
            $transactionTypes = collect();
            session()->now('error', 'Database Error: '.$e->getMessage());
        }

        return view('admin.account-logs', compact(
            'logs', 
            'accounts', 
            'totalDebit', 
            'totalCredit',
            'transactionTypes'
        ));
    }

    /**
     * Helper method to create an account log entry
     * This should be called from other controllers when transactions affect account balances
     */
    public static function createLog(
        string $date,
        int $accountId,
        string $transactionType,
        ?string $description,
        float $debitAmount,
        float $creditAmount,
        float $balanceAfter,
        ?int $referenceId = null,
        ?string $referenceType = null
    ): AccountLog {
        return AccountLog::create([
            'transaction_date' => $date,
            'account_id' => $accountId,
            'transaction_type' => $transactionType,
            'description' => $description,
            'debit_amount' => $debitAmount,
            'credit_amount' => $creditAmount,
            'balance_after' => $balanceAfter,
            'reference_id' => $referenceId,
            'reference_type' => $referenceType,
        ]);
    }

    /**
     * Rebuild account logs from existing data
     * Useful for initial population or data correction
     */
    public function rebuild()
    {
        try {
            DB::beginTransaction();
            
            // Clear existing logs
            AccountLog::truncate();
            
            // Process Daily Expenses (these affect account balances)
            $dailyExpenses = DailyExpense::whereNotNull('account_id')->get();
            foreach ($dailyExpenses as $expense) {
                $account = Account::find($expense->account_id);
                if ($account) {
                    // Calculate running balance
                    $previousLogs = AccountLog::where('account_id', $account->id)
                        ->where('transaction_date', '<=', $expense->expense_date)
                        ->orderBy('id', 'desc')
                        ->first();
                    $runningBalance = $previousLogs ? $previousLogs->balance_after : $account->balance_amount;
                    
                    // For historical reconstruction, we need to work backwards or use current balance
                    // This is a simplified approach
                    self::createLog(
                        $expense->expense_date,
                        $account->id,
                        'Daily Expense',
                        $expense->expense_details . ($expense->expense_by ? ' by ' . $expense->expense_by : ''),
                        $expense->expense_amount,
                        0,
                        $runningBalance - $expense->expense_amount,
                        $expense->id,
                        DailyExpense::class
                    );
                }
            }
            
            DB::commit();
            
            return redirect()->route('account-logs.index')->with('success', 'Account logs rebuilt successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rebuilding account logs: '.$e->getMessage());
            
            return back()->with('error', 'Error rebuilding logs: '.$e->getMessage());
        }
    }
}
