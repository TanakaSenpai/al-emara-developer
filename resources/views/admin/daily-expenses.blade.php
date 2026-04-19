@extends('layouts.admin')

@section('content')
<div class="bg-white shadow-sm min-h-[calc(100vh-6rem)] relative border border-gray-100">
    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
        <h1 class="text-xl text-gray-800 tracking-tight font-normal">Daily Expenses</h1>
        <button onclick="openAddModal()" class="bg-[#3eb27e] hover:bg-[#349c6d] text-white font-medium py-2 px-4 rounded text-sm transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Expense
        </button>
    </div>

    <!-- Add Expense Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-medium text-gray-800">Add Expense</h2>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('daily-expenses.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="expense_date" value="{{ date('Y-m-d') }}" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700">
                    </div>

                    <!-- Account Master -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700">Account Master <span class="text-red-500">*</span></label>
                        <select name="account_id" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                            <option value="">-Select-</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->account_number }} - {{ $account->description }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Expense Details -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Expense Details <span class="text-red-500">*</span></label>
                    <textarea name="expense_details" rows="3" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="Enter Expense Details"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Expense Amount -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700">Expense Amount <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="expense_amount" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                    </div>

                    <!-- Expense By -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700">Expense By</label>
                        <input type="text" name="expense_by" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="Person Name">
                    </div>

                    <!-- Voucher No/Other Docs -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700">Voucher No / Other Docs</label>
                        <input type="text" name="voucher_no" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="Voucher #">
                    </div>
                </div>

                <div class="mt-8 pt-4 border-t border-gray-50 flex gap-3">
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

    <!-- Expenses Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 font-medium">Date</th>
                    <th class="px-6 py-3 font-medium">Account</th>
                    <th class="px-6 py-3 font-medium">Expense Details</th>
                    <th class="px-6 py-3 font-medium text-right">Amount</th>
                    <th class="px-6 py-3 font-medium">Expense By</th>
                    <th class="px-6 py-3 font-medium">Voucher No</th>
                    <th class="px-6 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($expenses as $expense)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">{{ $expense->expense_date->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $expense->account ? $expense->account->account_number : '-' }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $expense->expense_details }}</td>
                    <td class="px-6 py-4 text-gray-700 text-right">{{ number_format($expense->expense_amount, 2) }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $expense->expense_by ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $expense->voucher_no ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick="openEditModal({{ $expense->id }}, '{{ $expense->expense_date->format('Y-m-d') }}', '{{ $expense->account_id }}', '{{ addslashes($expense->expense_details) }}', {{ $expense->expense_amount }}, '{{ $expense->expense_by }}', '{{ $expense->voucher_no }}')" class="text-blue-600 hover:text-blue-800" title="Edit">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            <form action="{{ route('daily-expenses.destroy', $expense) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this expense?');">
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
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No expenses found. Click "Add Expense" to create one.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-medium text-gray-800">Edit Expense</h2>
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
                    <input type="date" name="expense_date" id="edit_expense_date" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700">
                </div>

                <!-- Account Master -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Account Master <span class="text-red-500">*</span></label>
                    <select name="account_id" id="edit_account_id" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                        <option value="">-Select-</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->account_number }} - {{ $account->description }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Expense Details -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-medium text-gray-700">Expense Details <span class="text-red-500">*</span></label>
                <textarea name="expense_details" id="edit_expense_details" rows="3" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Expense Amount -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Expense Amount <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="expense_amount" id="edit_expense_amount" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700">
                </div>

                <!-- Expense By -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Expense By</label>
                    <input type="text" name="expense_by" id="edit_expense_by" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700">
                </div>

                <!-- Voucher No/Other Docs -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Voucher No / Other Docs</label>
                    <input type="text" name="voucher_no" id="edit_voucher_no" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700">
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-gray-50 flex gap-3">
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

    function openEditModal(id, date, accountId, details, amount, expenseBy, voucherNo) {
        document.getElementById('editForm').action = '/daily-expenses/' + id;
        document.getElementById('edit_expense_date').value = date;
        document.getElementById('edit_account_id').value = accountId || '';
        document.getElementById('edit_expense_details').value = details;
        document.getElementById('edit_expense_amount').value = amount;
        document.getElementById('edit_expense_by').value = expenseBy || '';
        document.getElementById('edit_voucher_no').value = voucherNo || '';
        document.getElementById('editModal').classList.remove('hidden');
        lucide.createIcons();
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });
</script>
@endsection
