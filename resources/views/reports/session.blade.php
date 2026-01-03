<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Session Report #{{ $session->id }}</title>
    @vite(['resources/css/app.css'])

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }
        }
    </style>
</head>

<body class="bg-white text-gray-900">
    <div class="mx-auto max-w-4xl p-6">
        <div class="no-print flex items-center justify-between mb-6">
            <a href="{{ route('dashboard.sessions.show', $session->id) }}" class="underline text-sm">Back</a>
            <button onclick="window.print()" class="rounded border px-3 py-2 text-sm">Print / Save as PDF</button>
        </div>

        <div class="border-b pb-4 mb-6">
            <h1 class="text-2xl font-semibold">Session Report</h1>
            <div class="mt-2 text-sm text-gray-700">
                <div><span class="font-medium">Session ID:</span> {{ $session->id }}</div>
                <div><span class="font-medium">Trainee:</span> {{ $session->trainee_name }}</div>
                <div><span class="font-medium">Date:</span> {{ optional($session->session_date)->format('Y-m-d H:i') }}
                </div>
                <div class="mt-2">
                    <span class="inline-block rounded border px-3 py-1 text-xs">Total:
                        {{ $session->total_score ?? '—' }}</span>
                    <span class="inline-block rounded border px-3 py-1 text-xs">Avg:
                        {{ $session->average_score ?? '—' }}</span>
                    <span class="inline-block rounded border px-3 py-1 text-xs">Shots: {{ $shots->count() }}</span>
                    <span class="inline-block rounded border px-3 py-1 text-xs">IoT: {{ $iotReadings->count() }}</span>
                </div>
            </div>
        </div>

        {{-- Recommendations --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold">AI Recommendations</h2>
            @if($recommendations->isEmpty())
                <p class="text-sm text-gray-600 mt-2">No recommendations available.</p>
            @else
                <ul class="list-disc pl-5 mt-2 text-sm space-y-1">
                    @foreach($recommendations as $r)
                        <li><span class="font-medium">{{ $r->recommendation_type }}:</span> {{ $r->message }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Shots table --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold">Shots</h2>
            <div class="overflow-x-auto mt-2">
                <table class="w-full text-sm border">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-2 border text-left">#</th>
                            <th class="p-2 border text-left">X</th>
                            <th class="p-2 border text-left">Y</th>
                            <th class="p-2 border text-left">Distance</th>
                            <th class="p-2 border text-left">Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shots as $i => $shot)
                            <tr>
                                <td class="p-2 border">{{ $i + 1 }}</td>
                                <td class="p-2 border">{{ $shot->x_coordinate }}</td>
                                <td class="p-2 border">{{ $shot->y_coordinate }}</td>
                                <td class="p-2 border">{{ $shot->distance_from_center }}</td>
                                <td class="p-2 border">{{ $shot->score }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-2 border text-gray-600">No shots found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- IoT table --}}
        <div>
            <h2 class="text-lg font-semibold">IoT Readings</h2>
            <div class="overflow-x-auto mt-2">
                <table class="w-full text-sm border">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-2 border text-left">Captured At</th>
                            <th class="p-2 border text-left">Distance (m)</th>
                            <th class="p-2 border text-left">Temp (°C)</th>
                            <th class="p-2 border text-left">Humidity (%)</th>
                            <th class="p-2 border text-left">Light (lux)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($iotReadings as $r)
                            <tr>l
                                <td class="p-2 border">{{ optional($r->captured_at)->format('Y-m-d H:i:s') ?? '—' }}</td>
                                <td class="p-2 border">{{ $r->distance_m ?? '—' }}</td>
                                <td class="p-2 border">{{ $r->temperature_c ?? '—' }}</td>
                                <td class="p-2 border">{{ $r->humidity_percent ?? '—' }}</td>
                                <td class="p-2 border">{{ $r->light_lux ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-2 border text-gray-600">No IoT readings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 text-xs text-gray-500">
            Generated on {{ now()->format('Y-m-d H:i') }}
        </div>
    </div>
</body>

</html>
