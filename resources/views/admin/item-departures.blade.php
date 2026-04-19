@extends('layouts.admin')

@section('content')
<div class="bg-white shadow-sm min-h-[calc(100vh-6rem)] relative border border-gray-100">
    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
        <h1 class="text-xl text-gray-800 tracking-tight font-normal">Item Departure</h1>
        <button onclick="openAddModal()" class="bg-[#3eb27e] hover:bg-[#349c6d] text-white font-medium py-2 px-4 rounded text-sm transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Item Departure
        </button>
    </div>

    <!-- Add Item Departure Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-medium text-gray-800">Add Item Departure</h2>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('item-departures.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

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
                        @foreach($items as $item)
                            <option value="{{ $item->item_name }}">{{ $item->item_name }}</option>
                        @endforeach
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
                <button type="button" onclick="closeAddModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-6 rounded text-sm transition-colors">
                    Cancel
                </button>
            </div>

        </form>
    </div>
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
                    <th class="px-6 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($departures as $departure)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">{{ $departure->date ? $departure->date->format('Y-m-d') : '-' }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $departure->item_master ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700 text-right">{{ number_format($departure->departure_qnty, 2) }}</td>
                    <td class="px-6 py-4 text-gray-700 text-right">{{ number_format($departure->prev_stock, 2) }}</td>
                    <td class="px-6 py-4 text-gray-700 text-right">{{ number_format($departure->balance_qnty, 2) }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $departure->notes ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick="openEditModal({{ $departure->id }}, '{{ $departure->date->format('Y-m-d') }}', '{{ $departure->item_master }}', {{ $departure->departure_qnty }}, {{ $departure->prev_stock }}, {{ $departure->balance_qnty }}, '{{ addslashes($departure->notes ?? '') }}')" class="text-blue-600 hover:text-blue-800" title="Edit">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            <form action="{{ route('item-departures.destroy', $departure) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this departure? This will return the quantity to stock.');">
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
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No item departures found. Click "Add Item Departure" to create one.</td>
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
            <h2 class="text-lg font-medium text-gray-800">Edit Item Departure</h2>
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

                <!-- Item Master -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Item Master <span class="text-red-500">*</span></label>
                    <select name="item_master" id="edit_item_master" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                        <option value="">-Select-</option>
                        @foreach($items as $item)
                            <option value="{{ $item->item_name }}">{{ $item->item_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-gray-50">
                <!-- Previous Stock -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Prev Stock</label>
                    <input type="number" step="0.01" name="prev_stock" id="edit_prev_stock" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300 bg-gray-50" placeholder="0.00" readonly>
                </div>

                <!-- Departure Qnty -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Departure Qnty <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" step="0.01" name="departure_qnty" id="edit_departure_qnty" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                    </div>
                    <p class="text-xs text-gray-400 italic mt-1 leading-tight">(Subtracts from Item Master stock)</p>
                </div>

                <!-- Balance Qnty -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Balance Qnty</label>
                    <input type="number" step="0.01" name="balance_qnty" id="edit_balance_qnty" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300 bg-gray-50" placeholder="0.00" readonly>
                </div>
            </div>

            <!-- Notes -->
            <div class="flex flex-col gap-2 pt-2 border-t border-gray-50 mt-2">
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

    function openEditModal(id, date, itemMaster, departureQnty, prevStock, balanceQnty, notes) {
        document.getElementById('editForm').action = '/item-departures/' + id;
        document.getElementById('edit_date').value = date;
        document.getElementById('edit_item_master').value = itemMaster;
        document.getElementById('edit_prev_stock').value = prevStock.toFixed(2);
        document.getElementById('edit_departure_qnty').value = departureQnty;
        document.getElementById('edit_balance_qnty').value = balanceQnty.toFixed(2);
        document.getElementById('edit_notes').value = notes;

        document.getElementById('editModal').classList.remove('hidden');
        lucide.createIcons();
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Edit modal calculations
    document.addEventListener('DOMContentLoaded', function() {
        const editItemSelect = document.getElementById('edit_item_master');
        const editPrevStock = document.getElementById('edit_prev_stock');
        const editDepartureQnty = document.getElementById('edit_departure_qnty');
        const editBalanceQnty = document.getElementById('edit_balance_qnty');

        if (editItemSelect) {
            editItemSelect.addEventListener('change', function() {
                const selectedItem = this.value;
                if (selectedItem) {
                    const item = itemsData.find(i => i.item_name === selectedItem);
                    if (item) {
                        editPrevStock.value = item.stock_balance.toFixed(2);
                        calculateEditBalance();
                    }
                } else {
                    editPrevStock.value = '';
                    editBalanceQnty.value = '';
                }
            });
        }

        if (editDepartureQnty) {
            editDepartureQnty.addEventListener('input', calculateEditBalance);
        }

        function calculateEditBalance() {
            const prev = parseFloat(editPrevStock.value) || 0;
            const dep = parseFloat(editDepartureQnty.value) || 0;
            const balance = prev - dep;
            if (editBalanceQnty) {
                editBalanceQnty.value = balance >= 0 ? balance.toFixed(2) : '';
            }
        }

        // Close modal on outside click
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const itemSelect = document.querySelector('select[name="item_master"]');
        const prevStockInput = document.querySelector('input[name="prev_stock"]');
        const departureQntyInput = document.querySelector('input[name="departure_qnty"]');
        const balanceQntyInput = document.querySelector('input[name="balance_qnty"]');

        // Load previous stock when item is selected
        if(itemSelect && prevStockInput) {
            itemSelect.addEventListener('change', function() {
                const selectedItemName = this.value;
                const prevStockInput = document.querySelector('input[name="prev_stock"]');

                if(selectedItemName) {
                    // Find the item data from the $items collection
                    @foreach($items as $item)
                    if(selectedItemName === '{{ $item->item_name }}') {
                        prevStockInput.value = '{{ number_format($item->stock_balance, 2) }}';
                        calculateBalance();
                    }
                    @endforeach
                } else {
                    prevStockInput.value = '';
                    balanceQntyInput.value = '';
                }
            });
        }

        // Calculate balance when departure quantity changes
        if(departureQntyInput && balanceQntyInput) {
            departureQntyInput.addEventListener('input', calculateBalance);
        }

        function calculateBalance() {
            const prevStock = parseFloat(prevStockInput.value) || 0;
            const departureQnty = parseFloat(departureQntyInput.value) || 0;
            const balance = prevStock - departureQnty;

            if(balanceQntyInput) {
                balanceQntyInput.value = balance >= 0 ? balance.toFixed(2) : '';
            }
        }
    });
</script>
@endsection
