@extends('layouts.admin')

@section('content')
<div class="bg-white shadow-sm min-h-[calc(100vh-6rem)] relative border border-gray-100">
    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
        <h1 class="text-xl text-gray-800 tracking-tight font-normal">Stock Entry</h1>
        <button onclick="openAddModal()" class="bg-[#3eb27e] hover:bg-[#349c6d] text-white font-medium py-2 px-4 rounded text-sm transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Stock Entry
        </button>
    </div>

    <!-- Add Stock Entry Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center overflow-y-auto">
        <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full mx-4 my-8 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-medium text-gray-800">Add Stock Entry</h2>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('stock-entry.store') }}" method="POST" class="p-6 space-y-8">
            @csrf

            <!-- Top Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-12 pb-6">
                <!-- Supplier Party Master -->
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <label class="md:w-48 text-sm font-medium text-gray-700">Supplier Party Master <span class="text-red-500">*</span></label>
                    <div class="flex-1">
                        <select name="supplier_master" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                            <option value="">-Select-</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->supplier_name }}">{{ $supplier->supplier_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Date -->
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <label class="md:w-48 text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                    <div class="flex-1 relative">
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700">
                    </div>
                </div>

                <!-- Date of Party Chalan -->
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <label class="md:w-48 text-sm font-medium text-gray-700">Date of Party Chalan</label>
                    <div class="flex-1 relative">
                        <input type="date" name="date_of_party_chalan" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-400 bg-white">
                    </div>
                </div>

                <!-- Ref. Party Chalan -->
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <label class="md:w-48 text-sm font-medium text-gray-700">Ref. Party Chalan</label>
                    <div class="flex-1">
                        <input type="text" name="ref_party_chalan" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300">
                    </div>
                </div>
            </div>

            <!-- SubForm Section -->
            <div class="pt-6">
                <h3 class="text-base font-medium text-gray-800 mb-4 flex items-center gap-1">SubForm <span class="text-red-500">*</span></h3>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[800px] text-left border-separate border-spacing-y-3">
                        <thead>
                            <tr>
                                <th class="w-8"></th>
                                <th class="font-medium text-gray-600 text-sm pb-2 w-48">Item Master</th>
                                <th class="font-medium text-gray-600 text-sm pb-2 w-28 text-right px-2">Qnty</th>
                                <th class="font-medium text-gray-600 text-sm pb-2 w-36 text-right px-2">Purchase Rate</th>
                                <th class="font-medium text-gray-600 text-sm pb-2 w-36 text-right px-2">Total Price</th>
                                <th class="font-medium text-gray-600 text-sm pb-2 w-32 text-right px-2">Old Stock</th>
                                <th class="font-medium text-gray-600 text-sm pb-2 w-32 text-right pl-2">New Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Row 1 -->
                            <tr class="group">
                                <td class="align-middle text-center px-1">
                                </td>
                                <td>
                                    <select name="item_master[]" class="w-full border border-gray-200 rounded px-2 py-1.5 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-[#3eb27e] bg-white font-medium">
                                        <option value="">-Select-</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->item_name }}">{{ $item->item_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-2">
                                    <input type="number" step="0.01" name="qnty[]" class="w-full border border-gray-200 rounded px-2 py-1.5 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-200" placeholder="#.##">
                                </td>
                                <td class="px-2">
                                    <input type="number" step="0.01" name="purchase_rate[]" class="w-full border border-gray-200 rounded px-2 py-1.5 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-200" placeholder="#.##">
                                </td>
                                <td class="px-2">
                                    <input type="number" step="0.01" name="total_price[]" class="w-full border border-gray-200 rounded px-2 py-1.5 text-sm text-right focus:outline-none text-gray-700 placeholder-gray-200 bg-gray-50 cursor-not-allowed" placeholder="#.##" readonly>
                                </td>
                                <td class="px-2">
                                    <input type="number" step="0.01" name="old_stock[]" class="w-full border border-gray-200 rounded px-2 py-1.5 text-sm text-right focus:outline-none text-gray-700 placeholder-gray-200 bg-gray-50 cursor-not-allowed" placeholder="#.##" readonly>
                                </td>
                                <td class="pl-2">
                                    <input type="number" step="0.01" name="new_stock[]" class="w-full border border-gray-200 rounded px-2 py-1.5 text-sm text-right focus:outline-none text-gray-700 placeholder-gray-200 bg-gray-50 cursor-not-allowed" placeholder="#.##" readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Add New Button & Divider -->
                <div class="mt-4 mb-6 flex justify-start border-t border-gray-100 pt-4">
                    <button type="button" id="add-new-row-btn" class="flex items-center justify-center gap-2 text-sm font-medium text-white bg-[#3eb27e] px-4 py-2 rounded-md hover:bg-[#349c6d] shadow-sm transition-all active:scale-95 cursor-pointer">
                        <i data-lucide="plus" class="w-4 h-4"></i> Add New Item Row
                    </button>
                </div>
            </div>

            <!-- Bottom Section -->
            <div class="flex flex-col md:flex-row justify-between items-start pt-2 gap-6">
                <div class="flex flex-col gap-2 w-full max-w-[300px]">
                    <div class="flex items-center gap-4">
                        <label class="w-24 text-sm font-medium text-gray-700">Sub Total <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="sub_total" class="flex-1 border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300 bg-gray-50" placeholder="0.00" readonly>
                    </div>
                </div>
            </div>

            <p class="text-xs text-gray-400 italic">(Will be added to Supplier's due balance; Items will update stock)</p>

            <div class="flex flex-col md:flex-row md:items-start gap-4">
                <label class="md:w-32 text-sm font-medium text-gray-700 md:mt-1">Notes</label>
                <div class="flex-1 max-w-2xl">
                    <textarea name="notes" rows="2" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300"></textarea>
                </div>
            </div>

            <div class="mt-8 pt-4 flex gap-3">
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

<!-- Stock Entries Table -->
<div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 font-medium">Date</th>
                    <th class="px-6 py-3 font-medium">Supplier</th>
                    <th class="px-6 py-3 font-medium">Ref Chalan</th>
                    <th class="px-6 py-3 font-medium">Items</th>
                    <th class="px-6 py-3 font-medium text-right">Total Amount</th>
                    <th class="px-6 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($stockEntries as $entry)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">{{ $entry->date ? $entry->date->format('Y-m-d') : '-' }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $entry->supplier_master ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $entry->ref_party_chalan ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700">
                        @if($entry->details && $entry->details->count() > 0)
                            @foreach($entry->details as $detail)
                                {{ $detail->item_master }} ({{ number_format($detail->qnty, 2) }})
                                @if(!$loop->last), @endif
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-700 text-right">{{ number_format($entry->sub_total, 2) }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick="openEditModal({{ $entry->id }})" class="text-blue-600 hover:text-blue-800" title="Edit">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            <form action="{{ route('stock-entry.destroy', $entry) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this stock entry? This will revert stock quantities.');">
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
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No stock entries found. Click "Add Stock Entry" to create one.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center overflow-y-auto">
    <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full mx-4 my-8 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-medium text-gray-800">Edit Stock Entry</h2>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-8">
            @csrf
            @method('PUT')

            <!-- Top Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-12 pb-6">
                <!-- Supplier Party Master -->
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <label class="md:w-48 text-sm font-medium text-gray-700">Supplier Party Master <span class="text-red-500">*</span></label>
                    <div class="flex-1">
                        <select name="supplier_master" id="edit_supplier_master" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                            <option value="">-Select-</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->supplier_name }}">{{ $supplier->supplier_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Date -->
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <label class="md:w-48 text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                    <div class="flex-1 relative">
                        <input type="date" name="date" id="edit_date" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700">
                    </div>
                </div>

                <!-- Date of Party Chalan -->
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <label class="md:w-48 text-sm font-medium text-gray-700">Date of Party Chalan</label>
                    <div class="flex-1 relative">
                        <input type="date" name="date_of_party_chalan" id="edit_date_of_party_chalan" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-400 bg-white">
                    </div>
                </div>

                <!-- Ref. Party Chalan -->
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <label class="md:w-48 text-sm font-medium text-gray-700">Ref. Party Chalan</label>
                    <div class="flex-1">
                        <input type="text" name="ref_party_chalan" id="edit_ref_party_chalan" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300">
                    </div>
                </div>
            </div>

            <!-- SubForm Section -->
            <div class="pt-6">
                <h3 class="text-base font-medium text-gray-800 mb-4 flex items-center gap-1">SubForm <span class="text-red-500">*</span></h3>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[800px] text-left border-separate border-spacing-y-3">
                        <thead>
                            <tr>
                                <th class="w-8"></th>
                                <th class="font-medium text-gray-600 text-sm pb-2 w-48">Item Master</th>
                                <th class="font-medium text-gray-600 text-sm pb-2 w-28 text-right px-2">Qnty</th>
                                <th class="font-medium text-gray-600 text-sm pb-2 w-36 text-right px-2">Purchase Rate</th>
                                <th class="font-medium text-gray-600 text-sm pb-2 w-36 text-right px-2">Total Price</th>
                                <th class="font-medium text-gray-600 text-sm pb-2 w-32 text-right px-2">Old Stock</th>
                                <th class="font-medium text-gray-600 text-sm pb-2 w-32 text-right pl-2">New Stock</th>
                            </tr>
                        </thead>
                        <tbody id="edit_table_body">
                        </tbody>
                    </table>
                </div>

                <!-- Add New Button & Divider -->
                <div class="mt-4 mb-6 flex justify-start border-t border-gray-100 pt-4">
                    <button type="button" id="edit_add_row_btn" class="flex items-center justify-center gap-2 text-sm font-medium text-white bg-[#3eb27e] px-4 py-2 rounded-md hover:bg-[#349c6d] shadow-sm transition-all active:scale-95 cursor-pointer">
                        <i data-lucide="plus" class="w-4 h-4"></i> Add New Item Row
                    </button>
                </div>
            </div>

            <!-- Bottom Section -->
            <div class="flex flex-col md:flex-row justify-between items-start pt-2 gap-6">
                <div class="flex flex-col gap-2 w-full max-w-[300px]">
                    <div class="flex items-center gap-4">
                        <label class="w-24 text-sm font-medium text-gray-700">Sub Total <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="sub_total" id="edit_sub_total" class="flex-1 border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300 bg-gray-50" placeholder="0.00" readonly>
                    </div>
                </div>
            </div>

            <p class="text-xs text-gray-400 italic">(Will be added to Supplier's due balance; Items will update stock)</p>

            <div class="flex flex-col md:flex-row md:items-start gap-4">
                <label class="md:w-32 text-sm font-medium text-gray-700 md:mt-1">Notes</label>
                <div class="flex-1 max-w-2xl">
                    <textarea name="notes" id="edit_notes" rows="2" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300"></textarea>
                </div>
            </div>

            <div class="mt-8 pt-4 flex gap-3">
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
    const stockEntriesData = @json($stockEntries->load('details'));
    const itemsData = @json($items);

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

    function openEditModal(entryId) {
        const entry = stockEntriesData.find(e => e.id === entryId);
        if (!entry) return;

        document.getElementById('editForm').action = '/stock-entry/' + entryId;
        document.getElementById('edit_supplier_master').value = entry.supplier_master || '';
        document.getElementById('edit_date').value = entry.date ? entry.date.substring(0, 10) : '';
        document.getElementById('edit_date_of_party_chalan').value = entry.date_of_party_chalan ? entry.date_of_party_chalan.substring(0, 10) : '';
        document.getElementById('edit_ref_party_chalan').value = entry.ref_party_chalan || '';
        document.getElementById('edit_sub_total').value = entry.sub_total || '0.00';
        document.getElementById('edit_notes').value = entry.notes || '';

        // Populate table rows
        const tbody = document.getElementById('edit_table_body');
        tbody.innerHTML = '';

        if (entry.details && entry.details.length > 0) {
            entry.details.forEach((detail, index) => {
                addEditRow(detail);
            });
        } else {
            addEditRow();
        }

        document.getElementById('editModal').classList.remove('hidden');
        lucide.createIcons();
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function addEditRow(detail = null) {
        const tbody = document.getElementById('edit_table_body');
        const rowCount = tbody.querySelectorAll('tr').length;

        const tr = document.createElement('tr');
        tr.className = 'group';

        let itemOptions = '<option value="">-Select-</option>';
        itemsData.forEach(item => {
            const selected = detail && detail.item_master === item.item_name ? 'selected' : '';
            itemOptions += `<option value="${item.item_name}" ${selected}>${item.item_name}</option>`;
        });

        tr.innerHTML = `
            <td class="align-middle text-center px-1">
                ${rowCount > 0 ? '<button type="button" class="delete-edit-row-btn flex items-center justify-center p-2 rounded-md text-red-500 hover:bg-red-50 hover:text-red-700 transition-all mx-auto active:scale-95 cursor-pointer" title="Remove"><i data-lucide="x" class="w-5 h-5 flex-shrink-0 text-red-500 pointer-events-none"></i></button>' : ''}
            </td>
            <td>
                <select name="item_master[]" class="edit-item-select w-full border border-gray-200 rounded px-2 py-1.5 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-[#3eb27e] bg-white font-medium">
                    ${itemOptions}
                </select>
            </td>
            <td class="px-2">
                <input type="number" step="0.01" name="qnty[]" value="${detail ? detail.qnty : ''}" class="edit-qnty w-full border border-gray-200 rounded px-2 py-1.5 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-200" placeholder="#.##">
            </td>
            <td class="px-2">
                <input type="number" step="0.01" name="purchase_rate[]" value="${detail ? detail.purchase_rate : ''}" class="edit-rate w-full border border-gray-200 rounded px-2 py-1.5 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-200" placeholder="#.##">
            </td>
            <td class="px-2">
                <input type="number" step="0.01" name="total_price[]" value="${detail ? detail.total_price : ''}" class="edit-total w-full border border-gray-200 rounded px-2 py-1.5 text-sm text-right focus:outline-none text-gray-700 placeholder-gray-200 bg-gray-50 cursor-not-allowed" placeholder="#.##" readonly>
            </td>
            <td class="px-2">
                <input type="number" step="0.01" name="old_stock[]" value="${detail ? detail.old_stock : ''}" class="edit-old-stock w-full border border-gray-200 rounded px-2 py-1.5 text-sm text-right focus:outline-none text-gray-700 placeholder-gray-200 bg-gray-50 cursor-not-allowed" placeholder="#.##" readonly>
            </td>
            <td class="pl-2">
                <input type="number" step="0.01" name="new_stock[]" value="${detail ? detail.new_stock : ''}" class="edit-new-stock w-full border border-gray-200 rounded px-2 py-1.5 text-sm text-right focus:outline-none text-gray-700 placeholder-gray-200 bg-gray-50 cursor-not-allowed" placeholder="#.##" readonly>
            </td>
        `;

        tbody.appendChild(tr);
        attachEditRowListeners(tr);
        lucide.createIcons({ root: tr });
    }

    function attachEditRowListeners(row) {
        const qntyInput = row.querySelector('.edit-qnty');
        const rateInput = row.querySelector('.edit-rate');
        const itemSelect = row.querySelector('.edit-item-select');
        const delBtn = row.querySelector('.delete-edit-row-btn');

        if (itemSelect) {
            itemSelect.addEventListener('change', function() {
                const selectedItemName = this.value;
                const oldStockInput = row.querySelector('.edit-old-stock');

                if (selectedItemName) {
                    const item = itemsData.find(i => i.item_name === selectedItemName);
                    if (item) {
                        oldStockInput.value = item.stock_balance;
                    }
                } else {
                    oldStockInput.value = '';
                }
                calculateEditRow(row);
            });
        }

        if (qntyInput) qntyInput.addEventListener('input', () => calculateEditRow(row));
        if (rateInput) rateInput.addEventListener('input', () => calculateEditRow(row));

        if (delBtn) {
            delBtn.addEventListener('click', () => {
                const rows = document.getElementById('edit_table_body').querySelectorAll('tr');
                if (rows.length > 1) {
                    row.remove();
                    calculateEditSubTotal();
                }
            });
        }
    }

    function calculateEditRow(row) {
        const qnty = parseFloat(row.querySelector('.edit-qnty')?.value) || 0;
        const rate = parseFloat(row.querySelector('.edit-rate')?.value) || 0;
        const totalInput = row.querySelector('.edit-total');
        const oldStock = parseFloat(row.querySelector('.edit-old-stock')?.value) || 0;
        const newStockInput = row.querySelector('.edit-new-stock');

        if (totalInput) {
            const total = qnty * rate;
            totalInput.value = total > 0 ? total.toFixed(2) : '';
        }

        if (newStockInput && qnty > 0) {
            newStockInput.value = (oldStock + qnty).toFixed(2);
        } else if (newStockInput) {
            newStockInput.value = '';
        }

        calculateEditSubTotal();
    }

    function calculateEditSubTotal() {
        let total = 0;
        document.querySelectorAll('.edit-total').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        const subTotalInput = document.getElementById('edit_sub_total');
        if (subTotalInput) subTotalInput.value = total > 0 ? total.toFixed(2) : '0.00';
    }

    document.getElementById('edit_add_row_btn')?.addEventListener('click', function() {
        addEditRow();
    });

    // Close modal when clicking outside
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const tableBody = document.querySelector('tbody');
        const addBtn = document.querySelector('.mt-2.mb-6 button');
        const subTotalInput = document.querySelector('input[name="sub_total"]');

        function calculateRow(row) {
            const qnty = parseFloat(row.querySelector('input[name="qnty[]"]')?.value) || 0;
            const rate = parseFloat(row.querySelector('input[name="purchase_rate[]"]')?.value) || 0;
            const totalInput = row.querySelector('input[name="total_price[]"]');
            const oldStock = parseFloat(row.querySelector('input[name="old_stock[]"]')?.value) || 0;
            const newStockInput = row.querySelector('input[name="new_stock[]"]');

            if(totalInput) {
                const total = qnty * rate;
                totalInput.value = total > 0 ? total.toFixed(2) : '';
            }

            if(newStockInput && qnty > 0) {
                newStockInput.value = (oldStock + qnty).toFixed(2);
            } else if (newStockInput) {
                newStockInput.value = '';
            }

            calculateSubTotal();
        }

        function calculateSubTotal() {
            let total = 0;
            document.querySelectorAll('input[name="total_price[]"]').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            if(subTotalInput) subTotalInput.value = total > 0 ? total.toFixed(2) : '0.00';
        }

        function attachListeners(row) {
            const qntyInput = row.querySelector('input[name="qnty[]"]');
            const rateInput = row.querySelector('input[name="purchase_rate[]"]');

            const delBtn = row.querySelector('.delete-row-btn');
            const itemSelect = row.querySelector('select[name="item_master[]"]');

            if(itemSelect) {
                itemSelect.addEventListener('change', function() {
                    const selectedItemName = this.value;
                    const oldStockInput = row.querySelector('input[name="old_stock[]"]');

                    if(selectedItemName) {
                        // Find the item data from the $items collection
                        @foreach($items as $item)
                        if(selectedItemName === '{{ $item->item_name }}') {
                            oldStockInput.value = '{{ number_format($item->stock_balance, 2) }}';
                        }
                        @endforeach
                        calculateRow(row);
                    } else {
                        oldStockInput.value = '';
                        calculateRow(row);
                    }
                });
            }

            if(qntyInput) qntyInput.addEventListener('input', () => calculateRow(row));
            if(rateInput) rateInput.addEventListener('input', () => calculateRow(row));

            if(delBtn) {
                delBtn.addEventListener('click', () => {
                    const rows = tableBody.querySelectorAll('tr[class="group"]');
                    if (rows.length > 1) {
                        row.remove();
                        calculateSubTotal();
                    }
                });
            }
        }

        document.querySelectorAll('tbody tr').forEach(row => {
            if(row.classList.contains('opacity-50')) {
                row.remove();
            } else {
                attachListeners(row);
            }
        });

        const trueAddBtn = document.querySelector('#add-new-row-btn');
        if(trueAddBtn) {
            trueAddBtn.addEventListener('click', function() {
                const rows = tableBody.querySelectorAll('tr[class="group"]');
                const firstRow = rows[0];
                const newRow = firstRow.cloneNode(true);

                const tdFirst = newRow.querySelector('td:first-child');
                tdFirst.innerHTML = '<button type="button" class="delete-row-btn flex items-center justify-center p-2 rounded-md text-red-500 hover:bg-red-50 hover:text-red-700 transition-all mx-auto active:scale-95 cursor-pointer" title="Remove"><i data-lucide="x" class="w-5 h-5 flex-shrink-0 text-red-500 pointer-events-none"></i></button>';

                newRow.querySelectorAll('input').forEach(input => input.value = '');
                newRow.querySelector('select').value = '';

                attachListeners(newRow);
                tableBody.appendChild(newRow);

                if(window.lucide) {
                    window.lucide.createIcons({
                        root: newRow
                    });
                }
            });
        }
    });
</script>
@endsection
