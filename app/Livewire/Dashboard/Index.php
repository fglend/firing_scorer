<?php

namespace App\Livewire\Dashboard;

use App\Models\ShootingSession;
use App\Models\Shot;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $sessions = ShootingSession::query()
            ->when($this->search !== '', function ($q) {
                $q->where('trainee_name', 'like', '%' . $this->search . '%');
            })
            ->withCount(['iotReadings', 'recommendations'])
            ->with(['target.shots']) // ✅ correct: session -> target -> shots
            ->latest('created_at')
            ->paginate($this->perPage);

        // ✅ Compute per-session totals/averages from shots.score
        $sessions->getCollection()->transform(function ($session) {
            $shots = optional($session->target)->shots ?? collect();

            $session->computed_total_score = (int) $shots->sum('score');

            $session->computed_average_score = $shots->isNotEmpty()
                ? round((float) $shots->avg('score'), 2)
                : null;

            $session->computed_shot_count = $shots->count();

            return $session;
        });

        // ✅ Summary computed from shots.score (authoritative source)
        // avg_average_score = average score per shot across all sessions
        $avgShotScore = Shot::query()->avg('score');

        // avg_total_score = average of per-session total scores
        // (simple approach; ok for small/medium data)
        $avgSessionTotal = ShootingSession::query()
            ->with(['target.shots'])
            ->get()
            ->map(fn($s) => (int) (optional($s->target)->shots?->sum('score') ?? 0))
            ->avg();

        $summary = [
            'total_sessions' => ShootingSession::count(),
            'avg_total_score' => round((float) ($avgSessionTotal ?? 0), 2),
            'avg_average_score' => round((float) ($avgShotScore ?? 0), 2),
        ];

        return view('livewire.dashboard.index', [
            'sessions' => $sessions,
            'summary' => $summary,
        ]);
    }
}
