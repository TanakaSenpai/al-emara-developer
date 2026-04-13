@extends('layouts.admin')

@section('content')
<div class="bg-white shadow-sm min-h-[calc(100vh-6rem)] relative border border-gray-100">
    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
        <h1 class="text-xl text-gray-800 tracking-tight font-normal">Item Departure</h1>
        <button onclick="toggleForm()" class="bg-[#3eb27e] hover:bg-[#349c6d] text-white font-medium py-2 px-4 rounded text-sm transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Item Departure
        </button>
    </div>

    <!-- Form (Hidden by default) -->
    <div id="departureForm" class="hidden p-6 border-b border-gray-100">
        <form action="#" method="POST" class="max-w-4xl space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Date -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700">
                </div>

                <!-- Item Master -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Item Master <span class="text-red-500">*</span></label>
                    <select name="item_master" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                        <option value="">-Select-</option>
                        <option value="1">Item A</option>
                        <option value="2">Item B</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-gray-50">
                <!-- Previous Stock -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Prev Stock</label>
                    <input type="number" step="0.01" name="prev_stock" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300 bg-gray-50" placeholder="0.00" readonly>
                </div>

                <!-- Departure Qnty -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Departure Qnty <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" step="0.01" name="departure_qnty" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                    </div>
                    <p class="text-xs text-gray-400 italic mt-1 leading-tight">(Subtracts from Item Master stock)</p>
                </div>

                <!-- Balance Qnty -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Balance Qnty</label>
                    <input type="number" step="0.01" name="balance_qnty" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300 bg-gray-50" placeholder="0.00" readonly>
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

    <!-- Departures Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 font-medium">Date</th>
                    <th class="px-6 py-3 font-medium">Item</th>
                    <th class="px-6 py-3 font-medium text-right">Departure Qty</th>
                    <th class="px-6 py-3 font-medium text-right">Prev Stock</th>
                    <th class="px-6 py-3 font-medium text-right">Balance</th>
                    <th class="px-6 py-3 font-medium">Notes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">2026-04-13</td>
                    <td class="px-6 py-4 text-gray-700">Item A</td>
                    <td class="px-6 py-4 text-gray-700 text-right">10.00</td>
                    <td class="px-6 py-4 text-gray-700 text-right">50.00</td>
                    <td class="px-6 py-4 text-gray-700 text-right">40.00</td>
                    <td class="px-6 py-4 text-gray-700">Sales</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">2026-04-11</td>
                    <td class="px-6 py-4 text-gray-700">Item B</td>
                    <td class="px-6 py-4 text-gray-700 text-right">5.00</td>
                    <td class="px-6 py-4 text-gray-700 text-right">25.00</td>
                    <td class="px-6 py-4 text-gray-700 text-right">20.00</td>
                    <td class="px-6 py-4 text-gray-700">Damaged</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleForm() {
        const form = document.getElementById('departureForm');
        form.classList.toggle('hidden');
    }
</script>
@endsection