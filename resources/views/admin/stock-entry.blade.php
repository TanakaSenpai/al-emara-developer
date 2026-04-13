@extends('layouts.admin')

@section('content')
<div class="bg-white shadow-sm min-h-[calc(100vh-6rem)] relative border border-gray-100">
    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
        <h1 class="text-xl text-gray-800 tracking-tight font-normal">Stock Entry</h1>
        <button onclick="toggleForm()" class="bg-[#3eb27e] hover:bg-[#349c6d] text-white font-medium py-2 px-4 rounded text-sm transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Stock Entry
        </button>
    </div>

    <!-- Form (Hidden by default) -->
    <div id="stockForm" class="hidden pb-20">
        <form action="#" method="POST" class="max-w-6xl space-y-8 p-6">
            
            <!-- Top Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-12 pb-6">
                <!-- Supplier Party Master -->
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <label class="md:w-48 text-sm font-medium text-gray-700">Supplier Party Master <span class="text-red-500">*</span></label>
                    <div class="flex-1">
                        <select name="supplier_master" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                            <option value="">-Select-</option>
                            <option value="1">Supplier A</option>
                            <option value="2">Supplier B</option>
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
                                        <option value="1">Item A</option>
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
                <button type="button" onclick="toggleForm()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-6 rounded text-sm transition-colors">
                    Cancel
                </button>
            </div>
            
        </form>
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
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">2026-04-13</td>
                    <td class="px-6 py-4 text-gray-700">Supplier A</td>
                    <td class="px-6 py-4 text-gray-700">CH-001</td>
                    <td class="px-6 py-4 text-gray-700">Item A (10), Item B (5)</td>
                    <td class="px-6 py-4 text-gray-700 text-right">1,500.00</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">2026-04-11</td>
                    <td class="px-6 py-4 text-gray-700">Supplier B</td>
                    <td class="px-6 py-4 text-gray-700">CH-002</td>
                    <td class="px-6 py-4 text-gray-700">Item C (20)</td>
                    <td class="px-6 py-4 text-gray-700 text-right">3,200.00</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleForm() {
        const form = document.getElementById('stockForm');
        form.classList.toggle('hidden');
    }

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
                    if(this.value) {
                        row.querySelector('input[name="old_stock[]"]').value = '15.00';
                        calculateRow(row);
                    } else {
                        row.querySelector('input[name="old_stock[]"]').value = '';
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