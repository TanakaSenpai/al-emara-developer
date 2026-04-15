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
        <form action="{{ route('supplier-master.store') }}" method="POST" class="max-w-3xl space-y-6">
            @csrf

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
                    <th class="px-6 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($suppliers as $supplier)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">{{ $supplier->supplier_name }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $supplier->address ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $supplier->mobile ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700 text-right">{{ number_format($supplier->due_balance, 2) }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick="openEditModal({{ $supplier->id }}, '{{ addslashes($supplier->supplier_name) }}', '{{ addslashes($supplier->mobile) }}', '{{ addslashes($supplier->address ?? '') }}', {{ $supplier->due_balance }})" class="text-blue-600 hover:text-blue-800" title="Edit">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            <form action="{{ route('supplier-master.destroy', $supplier) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No suppliers found. Click "Add Supplier" to create one.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-medium text-gray-800">Edit Supplier</h2>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="flex flex-col md:flex-row md:items-start md:pt-2 gap-4">
                <label class="md:w-56 text-sm font-medium text-gray-700 md:mt-1">Supplier Name <span class="text-red-500">*</span></label>
                <div class="flex-1">
                    <input type="text" name="supplier_name" id="edit_supplier_name" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="Enter Supplier Name">
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:items-start md:pt-2 gap-4">
                <label class="md:w-56 text-sm font-medium text-gray-700 md:mt-1">Address</label>
                <div class="flex-1">
                    <textarea name="address" id="edit_address" rows="3" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300"></textarea>
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <label class="md:w-56 text-sm font-medium text-gray-700">Mobile <span class="text-red-500">*</span></label>
                <div class="flex-1">
                    <input type="text" name="mobile" id="edit_mobile" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="Enter Mobile Number">
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <label class="md:w-56 text-sm font-medium text-gray-700">Due Balance</label>
                <div class="flex-1">
                    <input type="number" step="0.01" name="due_balance" id="edit_due_balance" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>
            </div>

            <div class="mt-10 pt-4 flex gap-3">
                <button type="submit" class="bg-[#3eb27e] hover:bg-[#349c6d] text-white font-medium py-2 px-6 rounded text-sm transition-colors shadow-sm">
                    Update
                </button>
                <button type="button" onclick="closeEditModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-6 rounded text-sm transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleForm() {
        const form = document.getElementById('supplierForm');
        form.classList.toggle('hidden');
    }

    function openEditModal(id, supplierName, mobile, address, dueBalance) {
        document.getElementById('editForm').action = '/supplier-master/' + id;
        document.getElementById('edit_supplier_name').value = supplierName;
        document.getElementById('edit_mobile').value = mobile;
        document.getElementById('edit_address').value = address;
        document.getElementById('edit_due_balance').value = dueBalance;
        document.getElementById('editModal').classList.remove('hidden');
        lucide.createIcons();
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Close modal on outside click
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });
</script>
@endsection
