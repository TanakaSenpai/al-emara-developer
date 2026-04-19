@extends('layouts.admin')

@section('content')
<div class="bg-white shadow-sm min-h-[calc(100vh-6rem)] relative border border-gray-100">
    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
        <h1 class="text-xl text-gray-800 tracking-tight font-normal">Budget Plan</h1>
        <button onclick="openAddModal()" class="bg-[#3eb27e] hover:bg-[#349c6d] text-white font-medium py-2 px-4 rounded text-sm transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Budget
        </button>
    </div>

    <!-- Add Budget Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-medium text-gray-800">Add Budget Plan</h2>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('budget-plan.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Date -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700">
                </div>

                <!-- Partner Master -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Partner Master <span class="text-red-500">*</span></label>
                    <select name="partner_master" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                        <option value="">-Select-</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner->partner_name }}">{{ $partner->partner_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Budget Details -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-medium text-gray-700">Budget Details <span class="text-red-500">*</span></label>
                <textarea name="budget_details" rows="3" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="Enter Budget Details"></textarea>
            </div>

            <div class="flex flex-col md:flex-row md:items-center gap-4 pt-4 border-t border-gray-50">
                <label class="md:w-48 text-sm font-medium text-gray-700">Charge Amount <span class="text-red-500">*</span></label>
                <div class="flex-1 max-w-sm relative">
                    <input type="number" step="0.01" name="charge_amount" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>
                <p class="text-xs text-gray-400 italic md:ml-2">(Will be added to Partner's total & due amount)</p>
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

<!-- Budget Table -->
<div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 font-medium">Date</th>
                    <th class="px-6 py-3 font-medium">Partner</th>
                    <th class="px-6 py-3 font-medium">Budget Details</th>
                    <th class="px-6 py-3 font-medium text-right">Charge Amount</th>
                    <th class="px-6 py-3 font-medium">Notes</th>
                    <th class="px-6 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($budgetPlans as $plan)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">{{ $plan->date ? $plan->date->format('Y-m-d') : '-' }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $plan->partner_master ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $plan->budget_details ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700 text-right">{{ number_format($plan->charge_amount, 2) }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $plan->notes ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick="openEditModal({{ $plan->id }}, '{{ $plan->date->format('Y-m-d') }}', '{{ addslashes($plan->partner_master) }}', '{{ addslashes($plan->budget_details) }}', {{ $plan->charge_amount }}, '{{ addslashes($plan->notes ?? '') }}')" class="text-blue-600 hover:text-blue-800" title="Edit">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            <form action="{{ route('budget-plan.destroy', $plan) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this budget plan? This will deduct the charge amount from partner.');">
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
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No budget plans found. Click "Add Budget" to create one.</td>
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
            <h2 class="text-lg font-medium text-gray-800">Edit Budget Plan</h2>
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

                <!-- Partner Master -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Partner Master <span class="text-red-500">*</span></label>
                    <select name="partner_master" id="edit_partner_master" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 bg-white">
                        <option value="">-Select-</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner->partner_name }}">{{ $partner->partner_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Budget Details -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-medium text-gray-700">Budget Details <span class="text-red-500">*</span></label>
                <textarea name="budget_details" id="edit_budget_details" rows="3" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300"></textarea>
            </div>

            <div class="flex flex-col md:flex-row md:items-center gap-4 pt-4 border-t border-gray-50">
                <label class="md:w-48 text-sm font-medium text-gray-700">Charge Amount <span class="text-red-500">*</span></label>
                <div class="flex-1 max-w-sm relative">
                    <input type="number" step="0.01" name="charge_amount" id="edit_charge_amount" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>
                <p class="text-xs text-gray-400 italic md:ml-2">(Will be added to Partner's total & due amount)</p>
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

    function openEditModal(id, date, partnerMaster, budgetDetails, chargeAmount, notes) {
        document.getElementById('editForm').action = '/budget-plan/' + id;
        document.getElementById('edit_date').value = date;
        document.getElementById('edit_partner_master').value = partnerMaster;
        document.getElementById('edit_budget_details').value = budgetDetails;
        document.getElementById('edit_charge_amount').value = chargeAmount;
        document.getElementById('edit_notes').value = notes;
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
