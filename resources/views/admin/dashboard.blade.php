@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <h2 class="text-lg font-medium text-gray-500 mb-4">Top teams this season</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
        <!-- Expenses Card -->
        <div class="bg-white rounded shadow-sm border border-gray-100 overflow-hidden flex flex-col h-36">
            <div class="flex-1 flex items-center justify-center pt-2">
                <span class="text-4xl font-normal text-[#ff4d42]">77,060.00</span>
            </div>
            <div class="bg-[#ff4d42] text-white text-center py-2 text-sm font-medium mt-auto">
                Expenses
            </div>
        </div>

        <!-- Bill Paid Card -->
        <div class="bg-white rounded shadow-sm border border-gray-100 overflow-hidden flex flex-col h-36">
            <div class="flex-1 flex items-center justify-center pt-2">
                <span class="text-4xl font-normal text-[#3b71ca]">0</span>
            </div>
            <div class="bg-[#3b71ca] text-white text-center py-2 text-sm font-medium mt-auto">
                Bill Paid
            </div>
        </div>

        <!-- Rod Card -->
        <div class="bg-white rounded shadow-sm border border-gray-100 overflow-hidden flex flex-col h-36">
            <div class="flex-1 flex items-center justify-center pt-2">
                <span class="text-4xl font-normal text-[#14a44d]">0.00</span>
            </div>
            <div class="bg-[#14a44d] text-white text-center py-2 text-sm font-medium mt-auto">
                Rod
            </div>
        </div>

        <!-- Cement Card -->
        <div class="bg-white rounded shadow-sm border border-gray-100 overflow-hidden flex flex-col h-36">
            <div class="flex-1 flex items-center justify-center pt-2">
                <span class="text-4xl font-normal text-[#333333]">0.00</span>
            </div>
            <div class="bg-[#333333] text-white text-center py-2 text-sm font-medium mt-auto">
                Cement
            </div>
        </div>
    </div>

    <h2 class="text-lg font-medium text-gray-500 mb-4">Goals by county</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Chart Placeholder 1 -->
        <div class="bg-white rounded shadow-sm border border-gray-100 p-8 flex flex-col items-center justify-center min-h-[220px]">
            <div class="relative w-32 h-24 mb-6 border-b-2 border-l-2 border-black flex flex-col justify-end gap-2 pr-2">
                <div class="bg-blue-400 h-4 w-12 opacity-80 mt-auto ml-1"></div>
                <div class="bg-blue-400 h-4 w-16 opacity-80 ml-1"></div>
                <div class="bg-blue-400 h-4 w-20 opacity-80 ml-1"></div>
                <div class="bg-blue-400 h-4 w-10 opacity-80 mb-1 ml-1"></div>
            </div>
            <p class="text-[13px] text-gray-600 text-center">Please configure the chart to be displayed here.</p>
        </div>

        <!-- Chart Placeholder 2 -->
        <div class="bg-white rounded shadow-sm border border-gray-100 p-8 flex flex-col items-center justify-center min-h-[220px]">
            <div class="relative w-32 h-24 mb-6 border-b-2 border-l-2 border-black flex flex-col justify-end gap-2 pr-2">
                <div class="bg-red-400 h-4 w-14 opacity-80 mt-auto ml-1"></div>
                <div class="bg-red-400 h-4 w-10 opacity-80 ml-1"></div>
                <div class="bg-red-400 h-4 w-18 opacity-80 ml-1"></div>
                <div class="bg-red-400 h-4 w-8 opacity-80 mb-1 ml-1"></div>
            </div>
            <p class="text-[13px] text-gray-600 text-center">Please configure the chart to be displayed here.</p>
        </div>

        <!-- Chart Placeholder 3 -->
        <div class="bg-white rounded shadow-sm border border-gray-100 p-8 flex flex-col items-center justify-center min-h-[220px]">
            <div class="relative w-32 h-24 mb-6 border-b-2 border-l-2 border-black flex flex-col justify-end gap-2 pr-2">
                <div class="bg-green-400 h-4 w-10 opacity-80 mt-auto ml-1"></div>
                <div class="bg-green-400 h-4 w-12 opacity-80 ml-1"></div>
                <div class="bg-green-400 h-4 w-16 opacity-80 ml-1"></div>
                <div class="bg-green-400 h-4 w-8 opacity-80 mb-1 ml-1"></div>
            </div>
            <p class="text-[13px] text-gray-600 text-center">Please configure the chart to be displayed here.</p>
        </div>
    </div>
</div>
@endsection
