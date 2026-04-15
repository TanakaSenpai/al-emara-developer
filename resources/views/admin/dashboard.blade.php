@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    @if($error ?? false)
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded">
            <p class="text-sm text-red-700">{{ $error }}</p>
        </div>
    @endif

    <h2 class="text-lg font-medium text-gray-500 mb-4">Financial Summary</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
        <!-- Expenses Card -->
        <div class="bg-white rounded shadow-sm border border-gray-100 overflow-hidden flex flex-col h-36">
            <div class="flex-1 flex items-center justify-center pt-2 px-2">
                <span class="text-3xl font-normal text-[#ff4d42]">{{ number_format($totalExpenses, 2) }}</span>
            </div>
            <div class="bg-[#ff4d42] text-white text-center py-2 text-sm font-medium mt-auto">
                Total Expenses
            </div>
        </div>

        <!-- Bill Paid Card -->
        <div class="bg-white rounded shadow-sm border border-gray-100 overflow-hidden flex flex-col h-36">
            <div class="flex-1 flex items-center justify-center pt-2 px-2">
                <span class="text-3xl font-normal text-[#3b71ca]">{{ number_format($totalBillPaid, 2) }}</span>
            </div>
            <div class="bg-[#3b71ca] text-white text-center py-2 text-sm font-medium mt-auto">
                Bill Paid
            </div>
        </div>

        <!-- Stock Value Card -->
        <div class="bg-white rounded shadow-sm border border-gray-100 overflow-hidden flex flex-col h-36">
            <div class="flex-1 flex items-center justify-center pt-2 px-2">
                <span class="text-3xl font-normal text-[#14a44d]">{{ number_format($totalStockValue, 2) }}</span>
            </div>
            <div class="bg-[#14a44d] text-white text-center py-2 text-sm font-medium mt-auto">
                Stock Value
            </div>
        </div>

        <!-- Partner Due Card -->
        <div class="bg-white rounded shadow-sm border border-gray-100 overflow-hidden flex flex-col h-36">
            <div class="flex-1 flex items-center justify-center pt-2 px-2">
                <span class="text-3xl font-normal text-[#333333]">{{ number_format($totalPartnerDue, 2) }}</span>
            </div>
            <div class="bg-[#333333] text-white text-center py-2 text-sm font-medium mt-auto">
                Partner Due
            </div>
        </div>
    </div>

    @if($topItems->count() > 0)
    <h2 class="text-lg font-medium text-gray-500 mb-4">Top Items by Stock</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
        @foreach($topItems as $item)
        <div class="bg-white rounded shadow-sm border border-gray-100 overflow-hidden flex flex-col h-36">
            <div class="flex-1 flex items-center justify-center pt-2 px-2">
                <span class="text-3xl font-normal text-gray-700">{{ number_format($item->stock_balance, 2) }}</span>
            </div>
            <div class="bg-gray-600 text-white text-center py-2 text-sm font-medium mt-auto truncate px-2">
                {{ $item->item_name }}
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Inventory Status -->
        <div class="bg-white rounded shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-800">Inventory Status</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 font-medium">Item</th>
                            <th class="px-6 py-3 font-medium text-right">Stock</th>
                            <th class="px-6 py-3 font-medium text-right">Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($itemsWithStock as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-700">{{ $item->item_name }}</td>
                            <td class="px-6 py-3 text-gray-700 text-right">{{ number_format($item->stock_balance, 2) }}</td>
                            <td class="px-6 py-3 text-gray-700 text-right">{{ number_format($item->purchase_rate, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">No items in stock</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-800">Recent Expenses</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 font-medium">Date</th>
                            <th class="px-6 py-3 font-medium">Details</th>
                            <th class="px-6 py-3 font-medium text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentExpenses as $expense)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-700">{{ $expense->expense_date ? $expense->expense_date->format('Y-m-d') : '-' }}</td>
                            <td class="px-6 py-3 text-gray-700">{{ Str::limit($expense->expense_details, 30) }}</td>
                            <td class="px-6 py-3 text-gray-700 text-right">{{ number_format($expense->expense_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">No recent expenses</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($recentBillPayments->count() > 0)
    <div class="bg-white rounded shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-medium text-gray-800">Recent Bill Payments</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 font-medium">Date</th>
                        <th class="px-6 py-3 font-medium">Supplier</th>
                        <th class="px-6 py-3 font-medium text-right">Paid Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentBillPayments as $payment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-gray-700">{{ $payment->date ? $payment->date->format('Y-m-d') : '-' }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $payment->supplier_name }}</td>
                        <td class="px-6 py-3 text-gray-700 text-right">{{ number_format($payment->paid_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
