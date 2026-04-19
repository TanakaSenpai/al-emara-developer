@extends('layouts.admin')

@section('content')
<div class="bg-white shadow-sm min-h-[calc(100vh-6rem)] relative border border-gray-100">
    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
        <h1 class="text-xl text-gray-800 tracking-tight font-normal">Partner Collection</h1>
        <button onclick="openAddModal()" class="bg-[#3eb27e] hover:bg-[#349c6d] text-white font-medium py-2 px-4 rounded text-sm transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Collection
        </button>
    </div>

    <!-- Add Collection Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-medium text-gray-800">Add Partner Collection</h2>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('partner-collection.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Date -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700">
                </div>

                <!-- Partners -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Partners <span class="text-red-500">*</span></label>
                    <select name="partners" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                        <option value="">-Select-</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner->partner_name }}">{{ $partner->partner_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Budget Plan -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Budget Plan</label>
                    <select name="budget_plan" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                        <option value="">-Select-</option>
                        @foreach($budgetPlans as $plan)
                            <option value="{{ $plan->budget_details }}">{{ $plan->budget_details }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-50 pt-4 mt-2">
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

                <!-- Total Due Balance -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Total Due Balance</label>
                    <input type="number" step="0.01" name="total_due_balance" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300 bg-gray-50" placeholder="0.00" readonly>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                <!-- Net Amount -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Net Amount</label>
                    <input type="number" step="0.01" name="net_amount" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>

                <!-- Extra Charges -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Extra Charges</label>
                    <input type="number" step="0.01" name="extra_charges" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>

                <!-- Total Paid Amount -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Total Paid Amount <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="total_paid_amount" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
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

<!-- Collections Table -->
<div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 font-medium">Date</th>
                    <th class="px-6 py-3 font-medium">Partner</th>
                    <th class="px-6 py-3 font-medium">Budget Plan</th>
                    <th class="px-6 py-3 font-medium">Account</th>
                    <th class="px-6 py-3 font-medium text-right">Net Amount</th>
                    <th class="px-6 py-3 font-medium text-right">Extra Charges</th>
                    <th class="px-6 py-3 font-medium text-right">Total Paid</th>
                    <th class="px-6 py-3 font-medium">Notes</th>
                    <th class="px-6 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($collections as $collection)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">{{ $collection->date ? $collection->date->format('Y-m-d') : '-' }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $collection->partners ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $collection->budget_plan ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $collection->account_master ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700 text-right">{{ number_format($collection->net_amount, 2) }}</td>
                    <td class="px-6 py-4 text-gray-700 text-right">{{ number_format($collection->extra_charges, 2) }}</td>
                    <td class="px-6 py-4 text-gray-700 text-right">{{ number_format($collection->total_paid_amount, 2) }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $collection->notes ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick="openEditModal({{ $collection->id }}, '{{ $collection->date->format('Y-m-d') }}', '{{ addslashes($collection->partners) }}', '{{ addslashes($collection->budget_plan ?? '') }}', '{{ addslashes($collection->account_master) }}', {{ $collection->total_due_balance }}, {{ $collection->net_amount }}, {{ $collection->extra_charges }}, {{ $collection->total_paid_amount }}, '{{ addslashes($collection->notes ?? '') }}')" class="text-blue-600 hover:text-blue-800" title="Edit">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            <form action="{{ route('partner-collection.destroy', $collection) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this collection? This will revert partner balances.');">
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
                    <td colspan="9" class="px-6 py-4 text-center text-gray-500">No partner collections found. Click "Add Collection" to create one.</td>
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
            <h2 class="text-lg font-medium text-gray-800">Edit Partner Collection</h2>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Date -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="date" id="edit_date" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700">
                </div>

                <!-- Partners -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Partners <span class="text-red-500">*</span></label>
                    <select name="partners" id="edit_partners" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                        <option value="">-Select-</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner->partner_name }}">{{ $partner->partner_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Budget Plan -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Budget Plan</label>
                    <select name="budget_plan" id="edit_budget_plan" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                        <option value="">-Select-</option>
                        @foreach($budgetPlans as $plan)
                            <option value="{{ $plan->budget_details }}">{{ $plan->budget_details }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-50 pt-4 mt-2">
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

                <!-- Total Due Balance -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Total Due Balance</label>
                    <input type="number" step="0.01" name="total_due_balance" id="edit_total_due_balance" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300 bg-gray-50" placeholder="0.00" readonly>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                <!-- Net Amount -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Net Amount</label>
                    <input type="number" step="0.01" name="net_amount" id="edit_net_amount" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>

                <!-- Extra Charges -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Extra Charges</label>
                    <input type="number" step="0.01" name="extra_charges" id="edit_extra_charges" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>

                <!-- Total Paid Amount -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Total Paid Amount <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="total_paid_amount" id="edit_total_paid_amount" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
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
    const partnersData = @json($partners);

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

    function openEditModal(id, date, partners, budgetPlan, accountMaster, totalDueBalance, netAmount, extraCharges, totalPaidAmount, notes) {
        document.getElementById('editForm').action = '/partner-collection/' + id;
        document.getElementById('edit_date').value = date;
        document.getElementById('edit_partners').value = partners;
        document.getElementById('edit_budget_plan').value = budgetPlan || '';
        document.getElementById('edit_account_master').value = accountMaster;
        document.getElementById('edit_total_due_balance').value = totalDueBalance.toFixed(2);
        document.getElementById('edit_net_amount').value = netAmount;
        document.getElementById('edit_extra_charges').value = extraCharges;
        document.getElementById('edit_total_paid_amount').value = totalPaidAmount;
        document.getElementById('edit_notes').value = notes;

        document.getElementById('editModal').classList.remove('hidden');
        lucide.createIcons();
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Edit modal partner change handler
    document.addEventListener('DOMContentLoaded', function() {
        const editPartnerSelect = document.getElementById('edit_partners');
        const editTotalDueBalance = document.getElementById('edit_total_due_balance');
        const editTotalPaidAmount = document.getElementById('edit_total_paid_amount');
        const editExtraCharges = document.getElementById('edit_extra_charges');
        const editNetAmount = document.getElementById('edit_net_amount');

        if (editPartnerSelect) {
            editPartnerSelect.addEventListener('change', function() {
                const selectedPartner = this.value;
                if (selectedPartner) {
                    const partner = partnersData.find(p => p.partner_name === selectedPartner);
                    if (editTotalDueBalance) {
                        editTotalDueBalance.value = partner ? partner.due_amount.toFixed(2) : '';
                    }
                } else if (editTotalDueBalance) {
                    editTotalDueBalance.value = '';
                }
            });
        }

        // Calculate net amount in edit modal
        function calculateEditNetAmount() {
            const paid = parseFloat(editTotalPaidAmount?.value) || 0;
            const extra = parseFloat(editExtraCharges?.value) || 0;
            const net = paid - extra;
            if (editNetAmount) {
                editNetAmount.value = net >= 0 ? net.toFixed(2) : '';
            }
        }

        if (editTotalPaidAmount) editTotalPaidAmount.addEventListener('input', calculateEditNetAmount);
        if (editExtraCharges) editExtraCharges.addEventListener('input', calculateEditNetAmount);

        // Close modal on outside click
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const partnerSelect = document.querySelector('select[name="partners"]');
        const totalDueBalanceInput = document.querySelector('input[name="total_due_balance"]');
        const totalPaidAmountInput = document.querySelector('input[name="total_paid_amount"]');
        const extraChargesInput = document.querySelector('input[name="extra_charges"]');
        const netAmountInput = document.querySelector('input[name="net_amount"]');

        // Load total due balance when partner is selected
        if(partnerSelect && totalDueBalanceInput) {
            partnerSelect.addEventListener('change', function() {
                const selectedPartnerName = this.value;

                if(selectedPartnerName) {
                    // Find the partner data from the $partners collection
                    @foreach($partners as $partner)
                    if(selectedPartnerName === '{{ $partner->partner_name }}') {
                        totalDueBalanceInput.value = '{{ number_format($partner->due_amount, 2) }}';
                    }
                    @endforeach
                } else {
                    totalDueBalanceInput.value = '';
                }
            });
        }

        // Calculate net amount when paid amount or extra charges change
        function calculateNetAmount() {
            const paidAmount = parseFloat(totalPaidAmountInput?.value) || 0;
            const extraCharges = parseFloat(extraChargesInput?.value) || 0;
            const netAmount = paidAmount - extraCharges;

            if(netAmountInput) {
                netAmountInput.value = netAmount >= 0 ? netAmount.toFixed(2) : '';
            }
        }

        if(totalPaidAmountInput) totalPaidAmountInput.addEventListener('input', calculateNetAmount);
        if(extraChargesInput) extraChargesInput.addEventListener('input', calculateNetAmount);
    });
</script>
@endsection
