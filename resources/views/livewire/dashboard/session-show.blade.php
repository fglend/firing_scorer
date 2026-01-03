{{-- resources/views/livewire/dashboard/session-show.blade.php --}}

<div class="space-y-6">

    {{-- Page Header --}}
    <div class="rounded-2xl border bg-white p-5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="min-w-0">
                <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Session #{{ $session->id }}
                </div>

                <h1 class="mt-1 text-xl font-semibold truncate">
                    {{ $session->trainee_name }}
                </h1>

                <p class="mt-1 text-sm text-gray-600">
                    {{ optional($session->session_date)->format('Y-m-d H:i') ?? '—' }}
                </p>

                {{-- Quick stats --}}
                <div class="mt-3 flex flex-wrap gap-2 text-xs">
                    <span class="rounded-full border px-3 py-1">Total: {{ $session->total_score ?? '—' }}</span>
                    <span class="rounded-full border px-3 py-1">Avg: {{ $session->average_score ?? '—' }}</span>
                    <span class="rounded-full border px-3 py-1">Shots: {{ $shots->count() }}</span>
                    <span class="rounded-full border px-3 py-1">IoT: {{ $iotReadings->count() }}</span>
                </div>

                {{-- Status badges --}}
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach(($badges ?? []) as $b)
                        @php
                            $cls = match ($b['tone'] ?? 'neutral') {
                                'good' => 'border-green-200 bg-green-50 text-green-800',
                                'warn' => 'border-yellow-200 bg-yellow-50 text-yellow-800',
                                'bad' => 'border-red-200 bg-red-50 text-red-800',
                                default => 'border-gray-200 bg-gray-50 text-gray-700',
                            };
                        @endphp
                        <span class="rounded-full border px-3 py-1 text-xs font-medium {{ $cls }}">
                            {{ $b['label'] ?? 'Status' }}
                        </span>
                    @endforeach
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-wrap items-center justify-end gap-2">
                <a href="{{ route('dashboard') }}" class="rounded-xl border px-3 py-2 text-sm hover:bg-gray-100">
                    Back
                </a>

                {{-- <a href="{{ route('dashboard.sessions.report', $session->id) }}"
                    class="rounded-xl border px-3 py-2 text-sm hover:bg-gray-100">
                    Report (Print/PDF)
                </a> --}}

                <button onclick="window.print()"
                    class="rounded-xl bg-gray-900 px-3 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                    Print
                </button>
            </div>
        </div>

        {{-- Export buttons --}}
        {{-- <div class="mt-4 flex flex-wrap gap-2">
            <a href="{{ route('dashboard.export.shots.json', $session->id) }}"
                class="rounded-xl border px-3 py-2 text-xs hover:bg-gray-100">
                Export Shots JSON
            </a>
            <a href="{{ route('dashboard.export.shots.csv', $session->id) }}"
                class="rounded-xl border px-3 py-2 text-xs hover:bg-gray-100">
                Export Shots CSV
            </a>
            <a href="{{ route('dashboard.export.iot.json', $session->id) }}"
                class="rounded-xl border px-3 py-2 text-xs hover:bg-gray-100">
                Export IoT JSON
            </a>
            <a href="{{ route('dashboard.export.iot.csv', $session->id) }}"
                class="rounded-xl border px-3 py-2 text-xs hover:bg-gray-100">
                Export IoT CSV
            </a>
        </div> --}}
    </div>

    {{-- Tabs --}}
    <div class="rounded-2xl border bg-white p-2">
        <div class="flex flex-wrap gap-2">
            <button wire:click="setTab('overview')"
                class="rounded-xl px-4 py-2 text-sm font-medium
                {{ ($tab ?? 'overview') === 'overview' ? 'bg-gray-900 text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                Overview
            </button>

            <button wire:click="setTab('iot')" class="rounded-xl px-4 py-2 text-sm font-medium
                {{ ($tab ?? 'overview') === 'iot' ? 'bg-gray-900 text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                IoT
            </button>

            <button wire:click="setTab('shots')" class="rounded-xl px-4 py-2 text-sm font-medium
                {{ ($tab ?? 'overview') === 'shots' ? 'bg-gray-900 text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                Shots
            </button>

            <button wire:click="setTab('ai')" class="rounded-xl px-4 py-2 text-sm font-medium
                {{ ($tab ?? 'overview') === 'ai' ? 'bg-gray-900 text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                AI
            </button>
        </div>
    </div>

    {{-- =========================
    OVERVIEW TAB
    ========================== --}}
    @if(($tab ?? 'overview') === 'overview')
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

            {{-- Summary card --}}
            <div class="rounded-2xl border bg-white p-5">
                <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Summary</div>
                <div class="mt-3 space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Total Score</span>
                        <span class="font-semibold">{{ $session->total_score ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Average Score</span>
                        <span class="font-semibold">{{ $session->average_score ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Shots Detected</span>
                        <span class="font-semibold">{{ $shots->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">IoT Readings</span>
                        <span class="font-semibold">{{ $iotReadings->count() }}</span>
                    </div>
                </div>
                <div class="mt-4 rounded-xl bg-gray-50 p-3 text-xs text-gray-600">
                    Tip: If lighting is low, bullet-hole detection can be less accurate. Check the IoT tab.
                </div>
            </div>

            {{-- Latest IoT snapshot --}}
            <div class="rounded-2xl border bg-white p-5">
                <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Latest IoT Snapshot</div>
                @php
                    $latest = $iotReadings->sortByDesc(fn($r) => $r->captured_at ?? $r->created_at)->first();
                @endphp

                @if(!$latest)
                    <p class="mt-3 text-sm text-gray-600">No IoT readings yet.</p>
                @else
                    <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                        <div class="rounded-xl border p-3">
                            <div class="text-xs text-gray-500">Captured</div>
                            <div class="font-medium">{{ optional($latest->captured_at)->format('H:i:s') ?? '—' }}</div>
                        </div>
                        <div class="rounded-xl border p-3">
                            <div class="text-xs text-gray-500">Distance (m)</div>
                            <div class="font-medium">{{ $latest->distance_m ?? '—' }}</div>
                        </div>
                        <div class="rounded-xl border p-3">
                            <div class="text-xs text-gray-500">Temp (°C)</div>
                            <div class="font-medium">{{ $latest->temperature_c ?? '—' }}</div>
                        </div>
                        <div class="rounded-xl border p-3">
                            <div class="text-xs text-gray-500">Humidity (%)</div>
                            <div class="font-medium">{{ $latest->humidity_percent ?? '—' }}</div>
                        </div>
                        <div class="rounded-xl border p-3 col-span-2">
                            <div class="text-xs text-gray-500">Light (lux)</div>
                            <div class="font-medium">{{ $latest->light_lux ?? '—' }}</div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Quick AI preview --}}
            <div class="rounded-2xl border bg-white p-5">
                <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">AI Notes</div>

                @if($recommendations->isEmpty())
                    <p class="mt-3 text-sm text-gray-600">No recommendations yet.</p>
                @else
                    <ul class="mt-3 space-y-2 text-sm">
                        @foreach($recommendations->take(4) as $r)
                            <li class="rounded-xl border p-3">
                                <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    {{ $r->recommendation_type }}
                                </div>
                                <div class="mt-1 text-gray-800">{{ $r->message }}</div>
                            </li>
                        @endforeach
                    </ul>

                    @if($recommendations->count() > 4)
                        <div class="mt-3 text-xs text-gray-500">
                            Showing 4 of {{ $recommendations->count() }}. Check the AI tab for full list.
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif

    {{-- =========================
    IOT TAB
    ========================== --}}
    @if(($tab ?? 'overview') === 'iot')
        {{-- IoT Line Charts --}}
        <div class="rounded-2xl border bg-white p-5 space-y-4">
            <div>
                <h2 class="text-lg font-semibold">IoT Trend</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Simple line charts (SVG-only). Helpful for checking sensor stability during the session.
                </p>
            </div>

            @php
                $w = 900;
                $h = 220;
                $p = 24;
                $cards = [
                    ['title' => 'Temperature (°C)', 'points' => $tempPoints ?? '', 'range' => $tempRange ?? ['min' => null, 'max' => null]],
                    ['title' => 'Humidity (%)', 'points' => $humPoints ?? '', 'range' => $humRange ?? ['min' => null, 'max' => null]],
                    ['title' => 'Light (lux)', 'points' => $luxPoints ?? '', 'range' => $luxRange ?? ['min' => null, 'max' => null]],
                    ['title' => 'Distance (m)', 'points' => $distPoints ?? '', 'range' => $distRange ?? ['min' => null, 'max' => null]],
                ];
            @endphp

            <div class="grid grid-cols-1 gap-4">
                @foreach($cards as $c)
                    <div class="rounded-2xl border p-4">
                        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                            <div class="font-medium text-sm">{{ $c['title'] }}</div>
                            <div class="text-xs text-gray-600">
                                @if($c['range']['min'] !== null)
                                    Min: {{ number_format($c['range']['min'], 2) }} • Max:
                                    {{ number_format($c['range']['max'], 2) }}
                                @else
                                    No data
                                @endif
                            </div>
                        </div>

                        @if(($c['points'] ?? '') === '')
                            <p class="mt-2 text-sm text-gray-600">Not enough readings to draw a line (need at least 2).</p>
                        @else
                            <div class="mt-3 overflow-x-auto">
                                <svg width="{{ $w }}" height="{{ $h }}" viewBox="0 0 {{ $w }} {{ $h }}"
                                    class="min-w-[900px] w-full">
                                    <rect x="0" y="0" width="{{ $w }}" height="{{ $h }}" fill="white"></rect>
                                    <line x1="{{ $p }}" y1="{{ $h - $p }}" x2="{{ $w - $p }}" y2="{{ $h - $p }}" stroke="#ddd"></line>
                                    <line x1="{{ $p }}" y1="{{ $p }}" x2="{{ $p }}" y2="{{ $h - $p }}" stroke="#ddd"></line>

                                    <polyline points="{{ $c['points'] }}" fill="none" stroke="black" stroke-width="2"></polyline>
                                </svg>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- IoT Table --}}
        <div class="rounded-2xl border bg-white overflow-hidden">
            <div class="border-b p-5">
                <h2 class="text-lg font-semibold">IoT Readings</h2>
                <p class="mt-1 text-sm text-gray-600">All captured sensor snapshots for this session.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="text-left px-5 py-3">Captured At</th>
                            <th class="text-left px-5 py-3">Distance (m)</th>
                            <th class="text-left px-5 py-3">Temp (°C)</th>
                            <th class="text-left px-5 py-3">Humidity (%)</th>
                            <th class="text-left px-5 py-3">Light (lux)</th>
                            <th class="text-left px-5 py-3">Device</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($iotReadings as $r)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3 whitespace-nowrap">
                                    {{ optional($r->captured_at)->format('Y-m-d H:i:s') ?? '—' }}
                                </td>
                                <td class="px-5 py-3">{{ $r->distance_m ?? '—' }}</td>
                                <td class="px-5 py-3">{{ $r->temperature_c ?? '—' }}</td>
                                <td class="px-5 py-3">{{ $r->humidity_percent ?? '—' }}</td>
                                <td class="px-5 py-3">{{ $r->light_lux ?? '—' }}</td>
                                <td class="px-5 py-3">{{ $r->device_id ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-5 py-10 text-center text-sm text-gray-600" colspan="6">
                                    No IoT readings yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- =========================
    SHOTS TAB
    ========================== --}}
    @if(($tab ?? 'overview') === 'shots')
        {{-- Heatmap + Explanation --}}
        <div class="rounded-2xl border bg-white p-5 space-y-3">
            <div>
                <h2 class="text-lg font-semibold">Shot Clustering</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Heatmap-like view (SVG-only). Darker blocks mean more hits in that area.
                </p>
            </div>

            @if($shots->isEmpty())
                <p class="text-sm text-gray-600">No shots found for this session.</p>
            @else
                <div class="flex flex-col lg:flex-row gap-5 items-start">
                    <div class="rounded-2xl border bg-white p-3">
                        <svg width="420" height="420" viewBox="0 0 420 420">
                            <rect x="0" y="0" width="420" height="420" fill="white"></rect>

                            {{-- Heat bins --}}
                            @foreach(($heatBins ?? []) as $b)
                                <rect x="{{ $b['x'] }}" y="{{ $b['y'] }}" width="{{ $b['w'] }}" height="{{ $b['h'] }}" fill="black"
                                    opacity="{{ $b['alpha'] }}"></rect>
                            @endforeach

                            {{-- Grid lines --}}
                            @php $grid = 14;
                            $cell = 420 / $grid; @endphp
                            @for($i = 0; $i <= $grid; $i++)
                                <line x1="{{ $i * $cell }}" y1="0" x2="{{ $i * $cell }}" y2="420" stroke="#eee"></line>
                                <line x1="0" y1="{{ $i * $cell }}" x2="420" y2="{{ $i * $cell }}" stroke="#eee"></line>
                            @endfor

                            {{-- Points overlay --}}
                            @foreach(($heatPts ?? []) as $pt)
                                <circle cx="{{ $pt['x'] }}" cy="{{ $pt['y'] }}" r="3" fill="black"></circle>
                            @endforeach

                            <rect x="0" y="0" width="420" height="420" fill="none" stroke="#ccc"></rect>
                        </svg>
                    </div>

                    <div class="flex-1 min-w-0 space-y-3">
                        <div class="rounded-2xl border p-4">
                            <div class="text-sm font-semibold">How to interpret</div>
                            <ul class="mt-2 list-disc pl-5 text-sm text-gray-700 space-y-1">
                                <li>Darker blocks = tighter grouping in that area.</li>
                                <li>Scattered blocks = inconsistent shots.</li>
                                <li>Consistent off-center cluster = possible sight alignment / stance issue.</li>
                            </ul>
                            <div class="mt-2 text-xs text-gray-500">
                                Note: The plot is normalized per session so it always fits the box.
                            </div>
                        </div>

                        <div class="rounded-2xl border p-4">
                            <div class="text-sm font-semibold">Quick count</div>
                            <div class="mt-2 grid grid-cols-2 gap-3 text-sm">
                                <div class="rounded-xl bg-gray-50 p-3">
                                    <div class="text-xs text-gray-500">Shots</div>
                                    <div class="text-lg font-semibold">{{ $shots->count() }}</div>
                                </div>
                                <div class="rounded-xl bg-gray-50 p-3">
                                    <div class="text-xs text-gray-500">Avg Score</div>
                                    <div class="text-lg font-semibold">{{ $session->average_score ?? '—' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Shots table --}}
        <div class="rounded-2xl border bg-white overflow-hidden">
            <div class="border-b p-5">
                <h2 class="text-lg font-semibold">Shots Table</h2>
                <p class="mt-1 text-sm text-gray-600">Auto-detected bullet holes with coordinates and score.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="text-left px-5 py-3">#</th>
                            <th class="text-left px-5 py-3">X</th>
                            <th class="text-left px-5 py-3">Y</th>
                            <th class="text-left px-5 py-3">Distance</th>
                            <th class="text-left px-5 py-3">Score</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($shots as $i => $shot)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3">{{ $i + 1 }}</td>
                                <td class="px-5 py-3">{{ $shot->x_coordinate }}</td>
                                <td class="px-5 py-3">{{ $shot->y_coordinate }}</td>
                                <td class="px-5 py-3">{{ $shot->distance_from_center }}</td>
                                <td class="px-5 py-3 font-semibold">{{ $shot->score }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-5 py-10 text-center text-sm text-gray-600" colspan="5">
                                    No shots found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- =========================
    AI TAB
    ========================== --}}
    @if(($tab ?? 'overview') === 'ai')
        <div class="rounded-2xl border bg-white p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold">AI Recommendations</h2>
                    <p class="mt-1 text-sm text-gray-600">Feedback based on shot patterns and session consistency.</p>
                </div>
            </div>

            @if($recommendations->isEmpty())
                <div class="mt-4 rounded-xl border bg-gray-50 p-4 text-sm text-gray-700">
                    No recommendations yet.
                </div>
            @else
                <div class="mt-4 grid grid-cols-1 gap-3">l
                    @foreach($recommendations as $r)
                        <div class="rounded-2xl border p-4">
                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                {{ $r->recommendation_type }}
                            </div>
                            <div class="mt-1 text-sm text-gray-800">
                                {{ $r->message }}
                            </div>
                            <div class="mt-2 text-xs text-gray-500">
                                {{ optional($r->created_at)->format('Y-m-d H:i') ?? '' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

</div>
