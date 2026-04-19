@extends('layouts.admin')

@section('content')
<div class="bg-white shadow-sm min-h-[calc(100vh-6rem)] relative border border-gray-100">
    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
        <h1 class="text-xl text-gray-800 tracking-tight font-normal">Bill Payment</h1>
        <button onclick="openAddModal()" class="bg-[#3eb27e] hover:bg-[#349c6d] text-white font-medium py-2 px-4 rounded text-sm transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Payment
        </button>
    </div>

    <!-- Add Payment Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-medium text-gray-800">Add Bill Payment</h2>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('bill-payments.store') }}" method="POST" class="p-6 space-y-6">
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
                <button type="button" onclick="closeAddModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-6 rounded text-sm transition-colors">
                    Cancel
                </button>
            </div>

        </form>
    </div>
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
                    <th class="px-6 py-3 font-medium">Actions</th>
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
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick="openEditModal({{ $payment->id }}, '{{ $payment->date->format('Y-m-d') }}', '{{ $payment->supplier_name }}', '{{ $payment->account_master }}', {{ $payment->paid_amount }}, '{{ addslashes($payment->paid_by ?? '') }}', '{{ addslashes($payment->notes ?? '') }}')" class="text-blue-600 hover:text-blue-800" title="Edit">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            <form action="{{ route('bill-payments.destroy', $payment) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this payment? This will add the amount back to supplier due balance.');">
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
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No bill payments found. Click "Add Payment" to create one.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-medium text-gray-800">Edit Bill Payment</h2>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Date -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="date" id="edit_date" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700">
                </div>

                <!-- Supplier Name -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Supplier Name <span class="text-red-500">*</span></label>
                    <select name="supplier_name" id="edit_supplier_name" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
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
                    <select name="account_master" id="edit_account_master" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                        <option value="">-Select-</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->account_number }}">{{ $account->account_number }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Paid By -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Paid By</label>
                    <input type="text" name="paid_by" id="edit_paid_by" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="Person Name">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-50 pt-4 mt-2">
                <!-- Last Balance -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Last Balance</label>
                    <input type="number" step="0.01" name="last_balance" id="edit_last_balance" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300 bg-gray-50" placeholder="0.00" readonly>
                </div>

                <!-- Paid Amount -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Paid Amount <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="paid_amount" id="edit_paid_amount" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>
            </div>

            <!-- Notes -->
            <div class="flex flex-col gap-2 pt-2">
                <label class="text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes" id="edit_notes" rows="4" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300"></textarea>
            </div>

            <div class="mt-8 pt-4 border-t border-gray-100 flex gap-3">
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
    const suppliersData = @json($suppliers);

    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
        lucide.createIcons();
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('addModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddModal();
        }
    });

    function openEditModal(id, date, supplierName, accountMaster, paidAmount, paidBy, notes) {
        document.getElementById('editForm').action = '/bill-payments/' + id;
        document.getElementById('edit_date').value = date;
        document.getElementById('edit_supplier_name').value = supplierName;
        document.getElementById('edit_account_master').value = accountMaster;
        document.getElementById('edit_paid_amount').value = paidAmount;
        document.getElementById('edit_paid_by').value = paidBy;
        document.getElementById('edit_notes').value = notes;

        // Load last balance for selected supplier
        const supplier = suppliersData.find(s => s.supplier_name === supplierName);
        document.getElementById('edit_last_balance').value = supplier ? supplier.due_balance.toFixed(2) : '0.00';

        document.getElementById('editModal').classList.remove('hidden');
        lucide.createIcons();
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Edit modal supplier change handler
    document.addEventListener('DOMContentLoaded', function() {
        const editSupplierSelect = document.getElementById('edit_supplier_name');
        const editLastBalance = document.getElementById('edit_last_balance');

        if (editSupplierSelect) {
            editSupplierSelect.addEventListener('change', function() {
                const selectedSupplier = this.value;
                if (selectedSupplier) {
                    const supplier = suppliersData.find(s => s.supplier_name === selectedSupplier);
                    if (editLastBalance) {
                        editLastBalance.value = supplier ? supplier.due_balance.toFixed(2) : '';
                    }
                } else if (editLastBalance) {
                    editLastBalance.value = '';
                }
            });
        }

        // Close modal on outside click
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    });

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
