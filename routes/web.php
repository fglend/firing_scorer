<?php

use App\Models\ShootingSession;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\Dashboard\SessionShow;

// Route::middleware(['web', 'auth'])->group(function () {
//     Route::get('/dashboard', DashboardIndex::class)->name('dashboard');
//     Route::get('/dashboard/sessions/{session}', SessionShow::class)->name('dashboard.sessions.show');
// });

Route::get('/dashboard', DashboardIndex::class)->name('dashboard');
Route::get('/dashboard/sessions/{session}', SessionShow::class)->name('dashboard.sessions.show');
// =========================
// EXPORT ROUTES (ADD THESE)
// =========================

// Shots JSON
Route::get('/dashboard/sessions/{session}/export/shots.json', function (ShootingSession $session) {
    $session->load('target.shots');

    $shots = optional($session->target)->shots?->map(fn($s) => [
        'id' => $s->id,
        'x' => $s->x_coordinate,
        'y' => $s->y_coordinate,
        'distance_from_center' => $s->distance_from_center,
        'score' => $s->score,
        'created_at' => optional($s->created_at)->toISOString(),
    ]) ?? collect();

    return response()->json($shots, 200, [], JSON_PRETTY_PRINT);
})->name('dashboard.export.shots.json');

// Shots CSV
Route::get('/dashboard/sessions/{session}/export/shots.csv', function (ShootingSession $session) {
    $session->load('target.shots');

    return response()->streamDownload(function () use ($session) {
        $out = fopen('php://output', 'w');
        fputcsv($out, ['id', 'x', 'y', 'distance_from_center', 'score', 'created_at']);

        foreach (optional($session->target)->shots ?? [] as $s) {
            fputcsv($out, [
                $s->id,
                $s->x_coordinate,
                $s->y_coordinate,
                $s->distance_from_center,
                $s->score,
                optional($s->created_at)->toDateTimeString(),
            ]);
        }
        fclose($out);
    }, 'shots_session_' . $session->id . '.csv');
})->name('dashboard.export.shots.csv');

// IoT JSON
Route::get('/dashboard/sessions/{session}/export/iot.json', function (ShootingSession $session) {
    $session->load('iotReadings');

    $iot = $session->iotReadings->map(fn($r) => [
        'id' => $r->id,
        'captured_at' => optional($r->captured_at)->toISOString(),
        'distance_m' => $r->distance_m,
        'temperature_c' => $r->temperature_c,
        'humidity_percent' => $r->humidity_percent,
        'light_lux' => $r->light_lux,
        'device_id' => $r->device_id,
    ]);

    return response()->json($iot, 200, [], JSON_PRETTY_PRINT);
})->name('dashboard.export.iot.json');

// IoT CSV
Route::get('/dashboard/sessions/{session}/export/iot.csv', function (ShootingSession $session) {
    $session->load('iotReadings');

    return response()->streamDownload(function () use ($session) {
        $out = fopen('php://output', 'w');
        fputcsv($out, [
            'id',
            'captured_at',
            'distance_m',
            'temperature_c',
            'humidity_percent',
            'light_lux',
            'device_id'
        ]);

        foreach ($session->iotReadings as $r) {
            fputcsv($out, [
                $r->id,
                optional($r->captured_at)->toDateTimeString(),
                $r->distance_m,
                $r->temperature_c,
                $r->humidity_percent,
                $r->light_lux,
                $r->device_id,
            ]);
        }
        fclose($out);
    }, 'iot_session_' . $session->id . '.csv');
})->name('dashboard.export.iot.csv');
