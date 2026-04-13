@extends('layouts.admin')

@section('content')
<div class="bg-white shadow-sm min-h-[calc(100vh-6rem)] relative border border-gray-100">
    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
        <h1 class="text-xl text-gray-800 tracking-tight font-normal">Supplier Master</h1>
        <button onclick="toggleForm()" class="bg-[#3eb27e] hover:bg-[#349c6d] text-white font-medium py-2 px-4 rounded text-sm transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Supplier
        </button>
    </div>

    <!-- Form (Hidden by default) -->
    <div id="supplierForm" class="hidden p-6 border-b border-gray-100">
        <form action="#" method="POST" class="max-w-3xl space-y-6">
            
            <div class="flex flex-col md:flex-row md:items-start md:pt-2 gap-4">
                <label class="md:w-56 text-sm font-medium text-gray-700 md:mt-1">Supplier Name <span class="text-red-500">*</span></label>
                <div class="flex-1">
                    <input type="text" name="supplier_name" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="Enter Supplier Name">
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:items-start md:pt-2 gap-4">
                <label class="md:w-56 text-sm font-medium text-gray-700 md:mt-1">Address</label>
                <div class="flex-1">
                    <textarea name="address" rows="3" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="Enter Address"></textarea>
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <label class="md:w-56 text-sm font-medium text-gray-700">Mobile <span class="text-red-500">*</span></label>
                <div class="flex-1">
                    <input type="text" name="mobile" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="Enter Mobile Number">
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <label class="md:w-56 text-sm font-medium text-gray-700">Due Balance</label>
                <div class="flex-1">
                    <input type="number" step="0.01" name="due_balance" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
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

    <!-- Suppliers Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 font-medium">Supplier Name</th>
                    <th class="px-6 py-3 font-medium">Address</th>
                    <th class="px-6 py-3 font-medium">Mobile</th>
                    <th class="px-6 py-3 font-medium text-right">Due Balance</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">Supplier A</td>
                    <td class="px-6 py-4 text-gray-700">123 Main Street, City</td>
                    <td class="px-6 py-4 text-gray-700">+1234567890</td>
                    <td class="px-6 py-4 text-gray-700 text-right">5,000.00</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">Supplier B</td>
                    <td class="px-6 py-4 text-gray-700">456 Oak Avenue, Town</td>
                    <td class="px-6 py-4 text-gray-700">+0987654321</td>
                    <td class="px-6 py-4 text-gray-700 text-right">2,500.00</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleForm() {
        const form = document.getElementById('supplierForm');
        form.classList.toggle('hidden');
    }
</script>
@endsection