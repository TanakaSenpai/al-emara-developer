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
        <form action="#" method="POST" class="max-w-4xl space-y-6">
            
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
                        <option value="1">Supplier A</option>
                        <option value="2">Supplier B</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Account Master -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Account Master <span class="text-red-500">*</span></label>
                    <select name="account_master" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                        <option value="">-Select-</option>
                        <option value="1">Main Account</option>
                        <option value="2">Petty Cash</option>
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
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">2026-04-13</td>
                    <td class="px-6 py-4 text-gray-700">Supplier A</td>
                    <td class="px-6 py-4 text-gray-700">Main Account</td>
                    <td class="px-6 py-4 text-gray-700 text-right">5,000.00</td>
                    <td class="px-6 py-4 text-gray-700">John Doe</td>
                    <td class="px-6 py-4 text-gray-700">Payment for invoice #123</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">2026-04-10</td>
                    <td class="px-6 py-4 text-gray-700">Supplier B</td>
                    <td class="px-6 py-4 text-gray-700">Petty Cash</td>
                    <td class="px-6 py-4 text-gray-700 text-right">2,500.00</td>
                    <td class="px-6 py-4 text-gray-700">Jane Smith</td>
                    <td class="px-6 py-4 text-gray-700">Partial payment</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleForm() {
        const form = document.getElementById('paymentForm');
        form.classList.toggle('hidden');
    }
</script>
@endsection