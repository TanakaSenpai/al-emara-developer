@extends('layouts.admin')

@section('content')
<div class="bg-white shadow-sm min-h-[calc(100vh-6rem)] relative border border-gray-100">
    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
        <h1 class="text-xl text-gray-800 tracking-tight font-normal">Account Masters</h1>
        <button onclick="toggleForm()" class="bg-[#3eb27e] hover:bg-[#349c6d] text-white font-medium py-2 px-4 rounded text-sm transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Account
        </button>
    </div>

    <!-- Form (Hidden by default) -->
    <div id="accountForm" class="hidden p-6 border-b border-gray-100">
        <form action="#" method="POST" class="max-w-3xl space-y-6">
            
            <div class="flex flex-col md:flex-row md:items-start md:pt-2 gap-4">
                <label class="md:w-56 text-sm font-medium text-gray-700 md:mt-1">Account Number <span class="text-red-500">*</span></label>
                <div class="flex-1">
                    <input type="text" name="account_number" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="Enter Account Number">
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:items-start md:pt-2 gap-4">
                <label class="md:w-56 text-sm font-medium text-gray-700 md:mt-1">Description <span class="text-red-500">*</span></label>
                <div class="flex-1">
                    <textarea name="description" rows="3" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="Enter Description"></textarea>
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <label class="md:w-56 text-sm font-medium text-gray-700">Balance Amount <span class="text-red-500">*</span></label>
                <div class="flex-1">
                    <input type="number" step="0.01" name="balance_amount" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>
            </div>

            <div class="mt-10 pt-4 flex gap-3">
                <button type="submit" class="bg-[#3eb27e] hover:bg-[#349c6d] text-white font-medium py-2 px-6 rounded text-sm transition-colors shadow-sm">
                    Submit
                </button>
                <button type="button" onclick="toggleForm()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-6 rounded text-sm transition-colors">
                    Cancel
                </button>
            </div>
            
        </form>
    </div>

    <!-- Accounts Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 font-medium">Account Number</th>
                    <th class="px-6 py-3 font-medium">Description</th>
                    <th class="px-6 py-3 font-medium text-right">Balance Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">ACC-001</td>
                    <td class="px-6 py-4 text-gray-700">Main Account</td>
                    <td class="px-6 py-4 text-gray-700 text-right">50,000.00</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">ACC-002</td>
                    <td class="px-6 py-4 text-gray-700">Petty Cash</td>
                    <td class="px-6 py-4 text-gray-700 text-right">10,000.00</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleForm() {
        const form = document.getElementById('accountForm');
        form.classList.toggle('hidden');
    }
</script>
@endsection