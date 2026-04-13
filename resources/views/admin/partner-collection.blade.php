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
        <form action="#" method="POST" class="max-w-4xl space-y-6">
            
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
                        <option value="1">Partner A</option>
                        <option value="2">Partner B</option>
                    </select>
                </div>

                <!-- Budget Plan -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Budget Plan</label>
                    <select name="budget_plan" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                        <option value="">-Select-</option>
                        <option value="1">Plan Q1</option>
                        <option value="2">Plan Q2</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-50 pt-4 mt-2">
                <!-- Account Master -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Account Master <span class="text-red-500">*</span></label>
                    <select name="account_master" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                        <option value="">-Select-</option>
                        <option value="1">Main Account</option>
                        <option value="2">Petty Cash</option>
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
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">2026-04-13</td>
                    <td class="px-6 py-4 text-gray-700">Partner A</td>
                    <td class="px-6 py-4 text-gray-700">Plan Q1</td>
                    <td class="px-6 py-4 text-gray-700">Main Account</td>
                    <td class="px-6 py-4 text-gray-700 text-right">5,000.00</td>
                    <td class="px-6 py-4 text-gray-700 text-right">0.00</td>
                    <td class="px-6 py-4 text-gray-700 text-right">5,000.00</td>
                    <td class="px-6 py-4 text-gray-700">Monthly collection</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">2026-04-10</td>
                    <td class="px-6 py-4 text-gray-700">Partner B</td>
                    <td class="px-6 py-4 text-gray-700">Plan Q2</td>
                    <td class="px-6 py-4 text-gray-700">Petty Cash</td>
                    <td class="px-6 py-4 text-gray-700 text-right">3,000.00</td>
                    <td class="px-6 py-4 text-gray-700 text-right">100.00</td>
                    <td class="px-6 py-4 text-gray-700 text-right">3,100.00</td>
                    <td class="px-6 py-4 text-gray-700">With extra charges</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleForm() {
        const form = document.getElementById('collectionForm');
        form.classList.toggle('hidden');
    }
</script>
@endsection