@extends('layouts.admin')

@section('content')
<div class="bg-white shadow-sm min-h-[calc(100vh-6rem)] relative border border-gray-100">
    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
        <h1 class="text-xl text-gray-800 tracking-tight font-normal">Partner Collection</h1>
        <button onclick="toggleForm()" class="bg-[#3eb27e] hover:bg-[#349c6d] text-white font-medium py-2 px-4 rounded text-sm transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Collection
        </button>
    </div>

    <!-- Form (Hidden by default) -->
    <div id="collectionForm" class="hidden p-6 border-b border-gray-100">
        <form action="{{ route('partner-collection.store') }}" method="POST" class="max-w-4xl space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Date -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700">
                </div>

                <!-- Partners -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Partners <span class="text-red-500">*</span></label>
                    <select name="partners" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                        <option value="">-Select-</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner->partner_name }}">{{ $partner->partner_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Budget Plan -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Budget Plan</label>
                    <select name="budget_plan" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                        <option value="">-Select-</option>
                        @foreach($budgetPlans as $plan)
                            <option value="{{ $plan->budget_details }}">{{ $plan->budget_details }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-50 pt-4 mt-2">
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
                
                <!-- Total Due Balance -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Total Due Balance</label>
                    <input type="number" step="0.01" name="total_due_balance" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300 bg-gray-50" placeholder="0.00" readonly>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                <!-- Net Amount -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Net Amount</label>
                    <input type="number" step="0.01" name="net_amount" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>
                
                <!-- Extra Charges -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Extra Charges</label>
                    <input type="number" step="0.01" name="extra_charges" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>

                <!-- Total Paid Amount -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Total Paid Amount <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="total_paid_amount" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>
            </div>

            <!-- Notes -->
            <div class="flex flex-col gap-2 pt-2 border-t border-gray-50 mt-2">
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

    <!-- Collections Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 font-medium">Date</th>
                    <th class="px-6 py-3 font-medium">Partner</th>
                    <th class="px-6 py-3 font-medium">Budget Plan</th>
                    <th class="px-6 py-3 font-medium">Account</th>
                    <th class="px-6 py-3 font-medium text-right">Net Amount</th>
                    <th class="px-6 py-3 font-medium text-right">Extra Charges</th>
                    <th class="px-6 py-3 font-medium text-right">Total Paid</th>
                    <th class="px-6 py-3 font-medium">Notes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($collections as $collection)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">{{ $collection->date ? $collection->date->format('Y-m-d') : '-' }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $collection->partners ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $collection->budget_plan ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $collection->account_master ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700 text-right">{{ number_format($collection->net_amount, 2) }}</td>
                    <td class="px-6 py-4 text-gray-700 text-right">{{ number_format($collection->extra_charges, 2) }}</td>
                    <td class="px-6 py-4 text-gray-700 text-right">{{ number_format($collection->total_paid_amount, 2) }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $collection->notes ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">No partner collections found. Click "Add Collection" to create one.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleForm() {
        const form = document.getElementById('collectionForm');
        form.classList.toggle('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const partnerSelect = document.querySelector('select[name="partners"]');
        const totalDueBalanceInput = document.querySelector('input[name="total_due_balance"]');
        const totalPaidAmountInput = document.querySelector('input[name="total_paid_amount"]');
        const extraChargesInput = document.querySelector('input[name="extra_charges"]');
        const netAmountInput = document.querySelector('input[name="net_amount"]');

        // Load total due balance when partner is selected
        if(partnerSelect && totalDueBalanceInput) {
            partnerSelect.addEventListener('change', function() {
                const selectedPartnerName = this.value;

                if(selectedPartnerName) {
                    // Find the partner data from the $partners collection
                    @foreach($partners as $partner)
                    if(selectedPartnerName === '{{ $partner->partner_name }}') {
                        totalDueBalanceInput.value = '{{ number_format($partner->due_amount, 2) }}';
                    }
                    @endforeach
                } else {
                    totalDueBalanceInput.value = '';
                }
            });
        }

        // Calculate net amount when paid amount or extra charges change
        function calculateNetAmount() {
            const paidAmount = parseFloat(totalPaidAmountInput?.value) || 0;
            const extraCharges = parseFloat(extraChargesInput?.value) || 0;
            const netAmount = paidAmount - extraCharges;

            if(netAmountInput) {
                netAmountInput.value = netAmount >= 0 ? netAmount.toFixed(2) : '';
            }
        }

        if(totalPaidAmountInput) totalPaidAmountInput.addEventListener('input', calculateNetAmount);
        if(extraChargesInput) extraChargesInput.addEventListener('input', calculateNetAmount);
    });
</script>
@endsection