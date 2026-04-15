@extends('layouts.admin')

@section('content')
<div class="bg-white shadow-sm min-h-[calc(100vh-6rem)] relative border border-gray-100">
    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
        <h1 class="text-xl text-gray-800 tracking-tight font-normal">Budget Plan</h1>
        <button onclick="toggleForm()" class="bg-[#3eb27e] hover:bg-[#349c6d] text-white font-medium py-2 px-4 rounded text-sm transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Budget
        </button>
    </div>

    <!-- Form (Hidden by default) -->
    <div id="budgetForm" class="hidden p-6 border-b border-gray-100">
        <form action="{{ route('budget-plan.store') }}" method="POST" class="max-w-3xl space-y-6">
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
                <button type="button" onclick="toggleForm()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-6 rounded text-sm transition-colors">
                    Cancel
                </button>
            </div>
            
        </form>
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
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No budget plans found. Click "Add Budget" to create one.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleForm() {
        const form = document.getElementById('budgetForm');
        form.classList.toggle('hidden');
    }
</script>
@endsection