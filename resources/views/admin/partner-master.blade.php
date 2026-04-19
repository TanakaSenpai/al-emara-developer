@extends('layouts.admin')

@section('content')
<div class="bg-white shadow-sm min-h-[calc(100vh-6rem)] relative border border-gray-100">
    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
        <h1 class="text-xl text-gray-800 tracking-tight font-normal">Partner Master</h1>
        <button onclick="openAddModal()" class="bg-[#3eb27e] hover:bg-[#349c6d] text-white font-medium py-2 px-4 rounded text-sm transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Partner
        </button>
    </div>

    <!-- Error Messages -->
    @if($error ?? false)
        <div class="px-6 py-4 border-b border-red-200 bg-red-50">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ $error }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="px-6 py-4 border-b border-red-200 bg-red-50">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="px-6 py-4 border-b border-red-200 bg-red-50">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-400"></i>
                </div>
                <div class="ml-3">
                    <ul class="text-sm text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="px-6 py-4 border-b border-green-200 bg-green-50">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Add Partner Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-medium text-gray-800">Add Partner</h2>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('partner-master.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

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
                <button type="button" onclick="closeAddModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-6 rounded text-sm transition-colors">
                    Cancel
                </button>
            </div>

        </form>
    </div>
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
                    <th class="px-6 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($partners as $partner)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">{{ $partner->partner_name }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $partner->mobile ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $partner->address ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700 text-right">{{ number_format($partner->total_charges, 2) }}</td>
                    <td class="px-6 py-4 text-gray-700 text-right">{{ number_format($partner->paid_amount, 2) }}</td>
                    <td class="px-6 py-4 text-gray-700 text-right">{{ number_format($partner->due_amount, 2) }}</td>
                    <td class="px-6 py-4 text-gray-700 text-right">{{ number_format($partner->extra_amount, 2) }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick="openEditModal({{ $partner->id }}, '{{ addslashes($partner->partner_name) }}', '{{ addslashes($partner->mobile) }}', '{{ addslashes($partner->address ?? '') }}', {{ $partner->total_charges }}, {{ $partner->paid_amount }}, {{ $partner->due_amount }}, {{ $partner->extra_amount }}, '{{ addslashes($partner->notes ?? '') }}')" class="text-blue-600 hover:text-blue-800" title="Edit">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            <form action="{{ route('partner-master.destroy', $partner) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this partner?');">
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
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">No partners found. Click "Add Partner" to create one.</td>
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
            <h2 class="text-lg font-medium text-gray-800">Edit Partner</h2>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="flex flex-col md:flex-row md:items-start md:pt-2 gap-4">
                <label class="md:w-56 text-sm font-medium text-gray-700 md:mt-1">Partner Name <span class="text-red-500">*</span></label>
                <div class="flex-1">
                    <input type="text" name="partner_name" id="edit_partner_name" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="Enter Partner Name">
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <label class="md:w-56 text-sm font-medium text-gray-700">Mobile <span class="text-red-500">*</span></label>
                <div class="flex-1">
                    <input type="text" name="mobile" id="edit_mobile" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="Enter Mobile Number">
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:items-start md:pt-2 gap-4">
                <label class="md:w-56 text-sm font-medium text-gray-700 md:mt-1">Address</label>
                <div class="flex-1">
                    <textarea name="address" id="edit_address" rows="3" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300"></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-50">
                <!-- Total Charges -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Total Charges</label>
                    <input type="number" step="0.01" name="total_charges" id="edit_total_charges" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>

                <!-- Paid Amount -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Paid Amount</label>
                    <input type="number" step="0.01" name="paid_amount" id="edit_paid_amount" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>

                <!-- Due Amount -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Due Amount</label>
                    <input type="number" step="0.01" name="due_amount" id="edit_due_amount" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>

                <!-- Extra Amount -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Extra Amount</label>
                    <input type="number" step="0.01" name="extra_amount" id="edit_extra_amount" class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-right focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300" placeholder="0.00">
                </div>
            </div>

            <div class="flex flex-col gap-2 pt-2">
                <label class="text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes" id="edit_notes" rows="4" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-gray-700 placeholder-gray-300"></textarea>
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

    function openEditModal(id, partnerName, mobile, address, totalCharges, paidAmount, dueAmount, extraAmount, notes) {
        document.getElementById('editForm').action = '/partner-master/' + id;
        document.getElementById('edit_partner_name').value = partnerName;
        document.getElementById('edit_mobile').value = mobile;
        document.getElementById('edit_address').value = address;
        document.getElementById('edit_total_charges').value = totalCharges;
        document.getElementById('edit_paid_amount').value = paidAmount;
        document.getElementById('edit_due_amount').value = dueAmount;
        document.getElementById('edit_extra_amount').value = extraAmount;
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
