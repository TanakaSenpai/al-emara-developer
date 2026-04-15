@extends('layouts.admin')

@section('content')
<div class="bg-white shadow-sm min-h-[calc(100vh-6rem)] relative border border-gray-100">
    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
        <h1 class="text-xl text-gray-800 tracking-tight font-normal">Bill Payment</h1>
        <button onclick="toggleForm()" class="bg-[#3eb27e] hover:bg-[#349c6d] text-white font-medium py-2 px-4 rounded text-sm transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Payment
        </button>
    </div>

    <!-- Form (Hidden by default) -->
    <div id="paymentForm" class="hidden p-6 border-b border-gray-100">
        <form action="{{ route('bill-payments.store') }}" method="POST" class="max-w-4xl space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Date -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700">
                </div>

                <!-- Supplier Name -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Supplier Name <span class="text-red-500">*</span></label>
                    <select name="supplier_name" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                        <option value="">-Select-</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->supplier_name }}">{{ $supplier->supplier_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Account Master -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Account Master <span class="text-red-500">*</span></label>
                    <select name="account_master" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                        <option value="">-Select-</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->account_number }}">{{ $account->account_number }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Paid By -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Paid By</label>
                    <input type="text" name="paid_by" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="Person Name">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-50 pt-4 mt-2">
                <!-- Last Balance -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Last Balance</label>
                    <input type="number" step="0.01" name="last_balance" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300 bg-gray-50" placeholder="0.00" readonly>
                </div>

                <!-- Paid Amount -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Paid Amount <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="paid_amount" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>
            </div>

            <!-- Notes -->
            <div class="flex flex-col gap-2 pt-2">
                <label class="text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes" rows="4" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="Enter Notes"></textarea>
            </div>

            <div class="mt-8 pt-4 border-t border-gray-100 flex gap-3">
                <button type="submit" class="bg-[#3eb27e] hover:bg-[#349c6d] text-white font-medium py-2 px-6 rounded text-sm transition-colors shadow-sm">
                    Submit
                </button>
                <button type="button" onclick="toggleForm()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-6 rounded text-sm transition-colors">
                    Cancel
                </button>
            </div>
            
        </form>
    </div>

    <!-- Payments Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 font-medium">Date</th>
                    <th class="px-6 py-3 font-medium">Supplier</th>
                    <th class="px-6 py-3 font-medium">Account</th>
                    <th class="px-6 py-3 font-medium text-right">Paid Amount</th>
                    <th class="px-6 py-3 font-medium">Paid By</th>
                    <th class="px-6 py-3 font-medium">Notes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">{{ $payment->date ? $payment->date->format('Y-m-d') : '-' }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $payment->supplier_name ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $payment->account_master ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700 text-right">{{ number_format($payment->paid_amount, 2) }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $payment->paid_by ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $payment->notes ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No bill payments found. Click "Add Payment" to create one.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleForm() {
        const form = document.getElementById('paymentForm');
        form.classList.toggle('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const supplierSelect = document.querySelector('select[name="supplier_name"]');
        const lastBalanceInput = document.querySelector('input[name="last_balance"]');

        // Load last balance when supplier is selected
        if(supplierSelect && lastBalanceInput) {
            supplierSelect.addEventListener('change', function() {
                const selectedSupplierName = this.value;

                if(selectedSupplierName) {
                    // Find the supplier data from the $suppliers collection
                    @foreach($suppliers as $supplier)
                    if(selectedSupplierName === '{{ $supplier->supplier_name }}') {
                        lastBalanceInput.value = '{{ number_format($supplier->due_balance, 2) }}';
                    }
                    @endforeach
                } else {
                    lastBalanceInput.value = '';
                }
            });
        }
    });
</script>
@endsection