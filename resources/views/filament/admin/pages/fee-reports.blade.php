<x-filament-panels::page>
    <div class="rounded-xl border bg-white p-4">
        <h3 class="font-semibold mb-3">Report Filters</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <label class="text-sm">
                <span class="block text-gray-600 mb-1">From Date</span>
                <input type="date" wire:model.live="from_date" class="w-full rounded border-gray-300" />
            </label>
            <label class="text-sm">
                <span class="block text-gray-600 mb-1">To Date</span>
                <input type="date" wire:model.live="to_date" class="w-full rounded border-gray-300" />
            </label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        <div class="rounded-xl border p-4 bg-white">
            <div class="text-xs text-gray-500">Daily Collection</div>
            <div class="text-2xl font-bold">{{ number_format($this->getDailyCollectionTotal(), 2) }}</div>
        </div>

        <div class="rounded-xl border p-4 bg-white">
            <div class="text-xs text-gray-500">Collection (Selected Period)</div>
            <div class="text-2xl font-bold">{{ number_format($this->getPeriodCollectionTotal(), 2) }}</div>
        </div>

        <div class="rounded-xl border p-4 bg-white">
            <div class="text-xs text-gray-500">Outstanding Dues</div>
            <div class="text-2xl font-bold text-danger-600">{{ number_format($this->getOutstandingTotal(), 2) }}</div>
        </div>
    </div>

    <div class="mt-6 rounded-xl border bg-white p-4">
        <h3 class="font-semibold mb-3">Class-wise Outstanding</h3>

        <table class="w-full text-sm border-collapse">
            <thead>
            <tr>
                <th class="text-left border-b py-2">Class</th>
                <th class="text-right border-b py-2">Due</th>
                <th class="text-right border-b py-2">Paid</th>
                <th class="text-right border-b py-2">Outstanding</th>
            </tr>
            </thead>
            <tbody>
            @forelse($this->getClassWiseOutstanding() as $class => $row)
                <tr>
                    <td class="py-2 border-b">{{ $class }}</td>
                    <td class="py-2 border-b text-right">{{ number_format($row['due'], 2) }}</td>
                    <td class="py-2 border-b text-right">{{ number_format($row['paid'], 2) }}</td>
                    <td class="py-2 border-b text-right">{{ number_format($row['outstanding'], 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-3 text-center text-gray-500">No data available</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 rounded-xl border bg-white p-4">
        <h3 class="font-semibold mb-3">Student Statement / Ledger (Top 100)</h3>

        <table class="w-full text-sm border-collapse">
            <thead>
            <tr>
                <th class="text-left border-b py-2">Invoice</th>
                <th class="text-left border-b py-2">Student</th>
                <th class="text-left border-b py-2">Admission #</th>
                <th class="text-left border-b py-2">Status</th>
                <th class="text-right border-b py-2">Due</th>
                <th class="text-right border-b py-2">Paid</th>
                <th class="text-right border-b py-2">Balance</th>
            </tr>
            </thead>
            <tbody>
            @forelse($this->getStudentLedgerRows() as $row)
                <tr>
                    <td class="py-2 border-b">{{ $row['invoice_no'] }}</td>
                    <td class="py-2 border-b">{{ $row['student'] }}</td>
                    <td class="py-2 border-b">{{ $row['admission_no'] }}</td>
                    <td class="py-2 border-b">{{ strtoupper($row['status']) }}</td>
                    <td class="py-2 border-b text-right">{{ number_format($row['due'], 2) }}</td>
                    <td class="py-2 border-b text-right">{{ number_format($row['paid'], 2) }}</td>
                    <td class="py-2 border-b text-right">{{ number_format($row['balance'], 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-3 text-center text-gray-500">No records in selected range</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>

