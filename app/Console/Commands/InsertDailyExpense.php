<?php

namespace App\Console\Commands;

use App\Models\DailyExpense;
use Illuminate\Console\Command;

class InsertDailyExpense extends Command
{
    protected $signature = 'expense:insert 
                            {expense_date : The date of expense (Y-m-d)}
                            {expense_details : The expense details}
                            {expense_amount : The expense amount}
                            {--expense_by= : Person who made the expense}
                            {--voucher_no= : Voucher number}
                            {--account_id= : Account ID}';

    protected $description = 'Insert a new daily expense record';

    public function handle()
    {
        $expenseDate = $this->argument('expense_date');
        $expenseDetails = $this->argument('expense_details');
        $expenseAmount = $this->argument('expense_amount');
        $expenseBy = $this->option('expense_by');
        $voucherNo = $this->option('voucher_no');
        $accountId = $this->option('account_id');

        $expense = DailyExpense::create([
            'expense_date' => $expenseDate,
            'expense_details' => $expenseDetails,
            'expense_amount' => $expenseAmount,
            'expense_by' => $expenseBy ?: null,
            'voucher_no' => $voucherNo ?: null,
            'account_id' => $accountId ?: null,
        ]);

        $this->info('Daily expense created successfully!');
        $this->table(
            ['ID', 'Date', 'Details', 'Amount', 'By', 'Voucher No', 'Account ID'],
            [[
                $expense->id,
                $expense->expense_date,
                $expense->expense_details,
                $expense->expense_amount,
                $expense->expense_by ?? '-',
                $expense->voucher_no ?? '-',
                $expense->account_id ?? '-',
            ]]
        );

        return Command::SUCCESS;
    }
}
