@extends('layouts.admin')

@section('content')
<div class="bg-white shadow-sm min-h-[calc(100vh-6rem)] relative border border-gray-100">
    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
        <h1 class="text-xl text-gray-800 tracking-tight font-normal">Partner Master</h1>
        <button onclick="toggleForm()" class="bg-[#3eb27e] hover:bg-[#349c6d] text-white font-medium py-2 px-4 rounded text-sm transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Partner
        </button>
    </div>

    <!-- Form (Hidden by default) -->
    <div id="partnerForm" class="hidden p-6 border-b border-gray-100">
        <form action="#" method="POST" class="max-w-3xl space-y-6">
            
            <div class="flex flex-col md:flex-row md:items-start md:pt-2 gap-4">
                <label class="md:w-56 text-sm font-medium text-gray-700 md:mt-1">Partner Name <span class="text-red-500">*</span></label>
                <div class="flex-1">
                    <input type="text" name="partner_name" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="Enter Partner Name">
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <label class="md:w-56 text-sm font-medium text-gray-700">Mobile <span class="text-red-500">*</span></label>
                <div class="flex-1">
                    <input type="text" name="mobile" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="Enter Mobile Number">
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:items-start md:pt-2 gap-4">
                <label class="md:w-56 text-sm font-medium text-gray-700 md:mt-1">Address</label>
                <div class="flex-1">
                    <textarea name="address" rows="3" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="Enter Address"></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-50">
                <!-- Total Charges -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Total Charges</label>
                    <input type="number" step="0.01" name="total_charges" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>

                <!-- Paid Amount -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Paid Amount</label>
                    <input type="number" step="0.01" name="paid_amount" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>

                <!-- Due Amount -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Due Amount</label>
                    <input type="number" step="0.01" name="due_amount" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>

                <!-- Extra Amount -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Extra Amount</label>
                    <input type="number" step="0.01" name="extra_amount" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>
            </div>

            <div class="flex flex-col gap-2 pt-2">
                <label class="text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes" rows="4" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="Enter Notes"></textarea>
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

    <!-- Partners Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 font-medium">Partner Name</th>
                    <th class="px-6 py-3 font-medium">Mobile</th>
                    <th class="px-6 py-3 font-medium">Address</th>
                    <th class="px-6 py-3 font-medium text-right">Total Charges</th>
                    <th class="px-6 py-3 font-medium text-right">Paid</th>
                    <th class="px-6 py-3 font-medium text-right">Due</th>
                    <th class="px-6 py-3 font-medium text-right">Extra</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">Partner A</td>
                    <td class="px-6 py-4 text-gray-700">+1234567890</td>
                    <td class="px-6 py-4 text-gray-700">123 Main Street, City</td>
                    <td class="px-6 py-4 text-gray-700 text-right">15,000.00</td>
                    <td class="px-6 py-4 text-gray-700 text-right">10,000.00</td>
                    <td class="px-6 py-4 text-gray-700 text-right">5,000.00</td>
                    <td class="px-6 py-4 text-gray-700 text-right">0.00</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">Partner B</td>
                    <td class="px-6 py-4 text-gray-700">+0987654321</td>
                    <td class="px-6 py-4 text-gray-700">456 Oak Avenue, Town</td>
                    <td class="px-6 py-4 text-gray-700 text-right">25,000.00</td>
                    <td class="px-6 py-4 text-gray-700 text-right">20,000.00</td>
                    <td class="px-6 py-4 text-gray-700 text-right">3,000.00</td>
                    <td class="px-6 py-4 text-gray-700 text-right">2,000.00</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleForm() {
        const form = document.getElementById('partnerForm');
        form.classList.toggle('hidden');
    }
</script>
@endsection