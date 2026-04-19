@extends('layouts.admin')

@section('content')
<div class="bg-white shadow-sm min-h-[calc(100vh-6rem)] relative border border-gray-100">
    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
        <h1 class="text-xl text-gray-800 tracking-tight font-normal">Account Log</h1>
        <div class="flex gap-2">
            <form action="{{ route('account-logs.rebuild') }}" method="POST" class="inline" onsubmit="return confirm('Are you sure? This will rebuild all logs from existing data.');">
                @csrf
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded text-sm transition-colors shadow-sm flex items-center gap-2">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    Rebuild Logs
                </button>
            </form>
        </div>
    </div>

    <!-- Filters -->
    <div class="p-6 border-b border-gray-100 bg-gray-50">
        <form action="{{ route('account-logs.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Account Filter -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Account</label>
                    <select name="account_id" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                        <option value="">All Accounts</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->account_number }} ({{ number_format($account->balance_amount, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Transaction Type Filter -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Transaction Type</label>
                    <select name="transaction_type" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                        <option value="">All Types</option>
                        @foreach($transactionTypes as $type)
                            <option value="{{ $type }}" {{ request('transaction_type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- From Date -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700">
                </div>

                <!-- To Date -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700">
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-[#3eb27e] hover:bg-[#349c6d] text-white font-medium py-2 px-6 rounded text-sm transition-colors shadow-sm">
                    Apply Filters
                </button>
                <a href="{{ route('account-logs.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-6 rounded text-sm transition-colors">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6 border-b border-gray-100">
        <div class="bg-red-50 border border-red-100 rounded-lg p-4">
            <p class="text-xs text-red-600 uppercase font-medium">Total Debit (Out)</p>
            <p class="text-2xl font-semibold text-red-700 mt-1">{{ number_format($totalDebit, 2) }}</p>
        </div>
        <div class="bg-green-50 border border-green-100 rounded-lg p-4">
            <p class="text-xs text-green-600 uppercase font-medium">Total Credit (In)</p>
            <p class="text-2xl font-semibold text-green-700 mt-1">{{ number_format($totalCredit, 2) }}</p>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
            <p class="text-xs text-blue-600 uppercase font-medium">Net Flow</p>
            <p class="text-2xl font-semibold {{ $totalCredit - $totalDebit >= 0 ? 'text-green-700' : 'text-red-700' }} mt-1">
                {{ number_format($totalCredit - $totalDebit, 2) }}
            </p>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 font-medium">Date</th>
                    <th class="px-6 py-3 font-medium">Account</th>
                    <th class="px-6 py-3 font-medium">Type</th>
                    <th class="px-6 py-3 font-medium">Description</th>
                    <th class="px-6 py-3 font-medium text-right text-red-600">Debit (Out)</th>
                    <th class="px-6 py-3 font-medium text-right text-green-600">Credit (In)</th>
                    <th class="px-6 py-3 font-medium text-right">Balance After</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700 whitespace-nowrap">{{ $log->transaction_date->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 text-gray-700">
                        <span class="font-medium">{{ $log->account->account_number ?? 'N/A' }}</span>
                        <span class="text-gray-400 text-xs block">{{ $log->account->description ?? '' }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-700">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if(in_array($log->transaction_type, ['Daily Expense', 'Bill Payment'])) bg-red-100 text-red-800
                            @elseif(in_array($log->transaction_type, ['Partner Collection', 'Deposit'])) bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $log->transaction_type }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-700 max-w-xs truncate" title="{{ $log->description }}">{{ $log->description ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700 text-right font-medium {{ $log->debit_amount > 0 ? 'text-red-600' : '' }}">
                        {{ $log->debit_amount > 0 ? number_format($log->debit_amount, 2) : '-' }}
                    </td>
                    <td class="px-6 py-4 text-gray-700 text-right font-medium {{ $log->credit_amount > 0 ? 'text-green-600' : '' }}">
                        {{ $log->credit_amount > 0 ? number_format($log->credit_amount, 2) : '-' }}
                    </td>
                    <td class="px-6 py-4 text-gray-700 text-right font-semibold">
                        {{ number_format($log->balance_after, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <i data-lucide="file-search" class="w-8 h-8 text-gray-300"></i>
                            <p>No account logs found.</p>
                            <p class="text-sm text-gray-400">Logs will be created automatically when transactions affect account balances.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $logs->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
