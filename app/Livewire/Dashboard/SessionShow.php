<?php

namespace App\Livewire\Dashboard;

use App\Models\ShootingSession;
use Illuminate\Support\Collection;
use Livewire\Component;

class SessionShow extends Component
{
    public ShootingSession $session;

    // Chart settings (simple and readable)
    public int $chartWidth = 900;
    public int $chartHeight = 220;
    public int $chartPadding = 24;

    // Heatmap settings
    public int $heatmapSize = 420;     // SVG size (square)
    public int $heatmapGrid = 14;      // 14x14 bins

    public string $tab = 'overview';

    public function mount(ShootingSession $session): void
    {
        $this->session = $session->load([
            'target.shots' => fn($q) => $q->orderBy('id'),
            'iotReadings' => fn($q) => $q->orderBy('captured_at'),
            'recommendations' => fn($q) => $q->latest(),
        ]);
    }

    /**
     * Build a simple SVG polyline points string for a metric.
     * Supported metrics: temperature_c, humidity_percent, light_lux, distance_m
     */

    public function setTab(string $tab): void
    {
        $allowed = ['overview', 'iot', 'shots', 'ai'];
        $this->tab = in_array($tab, $allowed, true) ? $tab : 'overview';
    }

    public function lineChartPoints(string $metric): string
    {
        $readings = $this->session->iotReadings
            ->filter(fn($r) => $r->$metric !== null)
            ->values();

        if ($readings->count() < 2) {
            return '';
        }

        $w = $this->chartWidth;
        $h = $this->chartHeight;
        $p = $this->chartPadding;

        $values = $readings->pluck($metric)->map(fn($v) => (float) $v);
        $minV = $values->min();
        $maxV = $values->max();

        // Avoid flat-line division by zero
        if ($minV == $maxV) {
            $minV -= 1;
            $maxV += 1;
        }

        $n = $values->count();
        $xStep = ($w - 2 * $p) / ($n - 1);

        $points = [];
        foreach ($values as $i => $v) {
            $x = $p + ($i * $xStep);

            // map v to y (invert because SVG y increases downward)
            $t = ($v - $minV) / ($maxV - $minV); // 0..1
            $y = ($h - $p) - ($t * ($h - 2 * $p));

            $points[] = round($x, 2) . ',' . round($y, 2);
        }

        return implode(' ', $points);
    }

    /** Provide axis labels for the chart. */
    public function chartRange(string $metric): array
    {
        $values = $this->session->iotReadings
            ->pluck($metric)
            ->filter(fn($v) => $v !== null)
            ->map(fn($v) => (float) $v)
            ->values();

        if ($values->isEmpty()) {
            return ['min' => null, 'max' => null];
        }

        return ['min' => $values->min(), 'max' => $values->max()];
    }

    /**
     * Heatmap-like binning for shot clustering using a simple grid.
     * We bin shots by their x/y, then return an array of rectangles with intensity.
     */
    public function heatmapBins(): array
    {
        $shots = optional($this->session->target)->shots ?? collect();
        if ($shots->isEmpty())
            return [];

        $grid = $this->heatmapGrid;

        // Get min/max to normalize into the heatmap box
        $minX = $shots->min('x_coordinate');
        $maxX = $shots->max('x_coordinate');
        $minY = $shots->min('y_coordinate');
        $maxY = $shots->max('y_coordinate');

        // Avoid divide-by-zero in case all points have same coordinate
        if ($minX == $maxX) {
            $minX -= 1;
            $maxX += 1;
        }
        if ($minY == $maxY) {
            $minY -= 1;
            $maxY += 1;
        }

        // Bin counts
        $counts = array_fill(0, $grid, array_fill(0, $grid, 0));

        foreach ($shots as $s) {
            $nx = ($s->x_coordinate - $minX) / ($maxX - $minX); // 0..1
            $ny = ($s->y_coordinate - $minY) / ($maxY - $minY); // 0..1

            // Clamp
            $nx = max(0, min(1, $nx));
            $ny = max(0, min(1, $ny));

            $bx = (int) floor($nx * ($grid - 1));
            $by = (int) floor($ny * ($grid - 1));

            $counts[$by][$bx] += 1;
        }

        // Find max bin for intensity scaling
        $maxCount = 0;
        for ($r = 0; $r < $grid; $r++) {
            for ($c = 0; $c < $grid; $c++) {
                $maxCount = max($maxCount, $counts[$r][$c]);
            }
        }
        if ($maxCount === 0)
            return [];

        // Build rectangles
        $size = $this->heatmapSize;
        $cell = $size / $grid;

        $bins = [];
        for ($r = 0; $r < $grid; $r++) {
            for ($c = 0; $c < $grid; $c++) {
                $count = $counts[$r][$c];
                if ($count === 0)
                    continue;

                // intensity 0.15..0.9 (visible but not too dark)
                $alpha = 0.15 + (0.75 * ($count / $maxCount));

                $bins[] = [
                    'x' => $c * $cell,
                    'y' => $r * $cell,
                    'w' => $cell,
                    'h' => $cell,
                    'alpha' => round($alpha, 3),
                    'count' => $count,
                ];
            }
        }

        return $bins;
    }

    /**
     * Convert shot points into SVG coordinates to overlay points on heatmap.
     */
    public function heatmapPoints(): array
    {
        $shots = optional($this->session->target)->shots ?? collect();
        if ($shots->isEmpty())
            return [];

        $size = $this->heatmapSize;

        $minX = $shots->min('x_coordinate');
        $maxX = $shots->max('x_coordinate');
        $minY = $shots->min('y_coordinate');
        $maxY = $shots->max('y_coordinate');

        if ($minX == $maxX) {
            $minX -= 1;
            $maxX += 1;
        }
        if ($minY == $maxY) {
            $minY -= 1;
            $maxY += 1;
        }

        $points = [];
        foreach ($shots as $s) {
            $nx = ($s->x_coordinate - $minX) / ($maxX - $minX);
            $ny = ($s->y_coordinate - $minY) / ($maxY - $minY);

            $nx = max(0, min(1, $nx));
            $ny = max(0, min(1, $ny));

            // map to SVG space
            $x = $nx * $size;
            $y = $ny * $size;

            $points[] = ['x' => round($x, 2), 'y' => round($y, 2)];
        }

        return $points;
    }

    public function render()
    {
        $target = $this->session->target;
        $shots = optional($target)->shots ?? collect();

        return view('livewire.dashboard.session-show', [
            'target' => $target,
            'shots' => $shots,
            'iotReadings' => $this->session->iotReadings,
            'recommendations' => $this->session->recommendations,

            // SVG helpers
            'tempPoints' => $this->lineChartPoints('temperature_c'),
            'humPoints' => $this->lineChartPoints('humidity_percent'),
            'luxPoints' => $this->lineChartPoints('light_lux'),
            'distPoints' => $this->lineChartPoints('distance_m'),

            'tempRange' => $this->chartRange('temperature_c'),
            'humRange' => $this->chartRange('humidity_percent'),
            'luxRange' => $this->chartRange('light_lux'),
            'distRange' => $this->chartRange('distance_m'),

            'heatBins' => $this->heatmapBins(),
            'heatPts' => $this->heatmapPoints(),
        ]); // adjust if your layout is different
    }
}
