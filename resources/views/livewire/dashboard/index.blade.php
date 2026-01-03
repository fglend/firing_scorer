<div class="space-y-6">
    {{-- Page Header --}}
    <div class="rounded-2xl border bg-white p-5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold">Training Dashboard</h1>
                <p class="mt-1 text-sm text-gray-600">Browse sessions, view scoring, and check IoT conditions.</p>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <input type="text" wire:model.live="search" placeholder="Search trainee name…"
                    class="w-full sm:w-72 rounded-xl border px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-gray-200" />

                <select wire:model.live="perPage"
                    class="w-full sm:w-28 rounded-xl border px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-gray-200">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="rounded-2xl border bg-white p-5">
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Sessions</div>
            <div class="mt-2 text-3xl font-semibold">{{ number_format($summary['total_sessions']) }}</div>
            <div class="mt-1 text-sm text-gray-600">Recorded training sessions</div>
        </div>

        <div class="rounded-2xl border bg-white p-5">
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Average Total Score</div>
            <div class="mt-2 text-3xl font-semibold">{{ number_format($summary['avg_total_score'], 2) }}</div>
            <div class="mt-1 text-sm text-gray-600">Across all sessions</div>
        </div>

        <div class="rounded-2xl border bg-white p-5">
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Average Score per Shot</div>
            <div class="mt-2 text-3xl font-semibold">{{ number_format($summary['avg_average_score'], 2) }}</div>
            <div class="mt-1 text-sm text-gray-600">Mean shot performance</div>
        </div>
    </div>

    {{-- Sessions Table --}}
    <div class="rounded-2xl border bg-white overflow-hidden">
        <div class="flex items-center justify-between gap-3 border-b p-5">
            <div>
                <div class="text-sm font-semibold">Sessions</div>
                <div class="mt-1 text-xs text-gray-600">Click “View” to open session details.</div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="text-left px-5 py-3">Date</th>
                        <th class="text-left px-5 py-3">Trainee</th>
                        <th class="text-left px-5 py-3">Total</th>
                        <th class="text-left px-5 py-3">Avg</th>
                        <th class="text-left px-5 py-3">IoT</th>
                        <th class="text-left px-5 py-3">AI</th>
                        <th class="text-right px-5 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($sessions as $s)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 whitespace-nowrap text-gray-700">
                                {{ optional($s->created_at)->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-5 py-3 font-medium">{{ $s->trainee_name }}</td>
                            <td class="px-5 py-3">{{ $s->computed_total_score ?? '—' }}</td>
                            <td class="px-5 py-3">{{ $s->computed_average_score ?? '—' }}</td>

                            <td class="px-5 py-3">
                                <span class="inline-flex items-center rounded-full border px-2 py-1 text-xs">
                                    {{ $s->iot_readings_count }}
                                </span>
                            </td>

                            <td class="px-5 py-3">
                                <span class="inline-flex items-center rounded-full border px-2 py-1 text-xs">
                                    {{ $s->recommendations_count }}
                                </span>
                            </td>

                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('dashboard.sessions.show', $s->id) }}"
                                    class="inline-flex items-center rounded-xl bg-gray-900 px-3 py-2 text-xs font-semibold text-white hover:bg-gray-800">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-5 py-10 text-center text-sm text-gray-600" colspan="7">
                                No sessions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $sessions->links() }}
        </div>
    </div>
</div>
