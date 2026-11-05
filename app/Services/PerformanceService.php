<?php

namespace App\Services;

use App\Models\Animal;
use App\Models\FeedingStage;

/**
 * Motor de Rendimiento del Engorde (Core).
 * En esta entrega (Administración) se usa para mostrar que los datos que
 * registra el admin alimentan los cálculos del Core.
 */
class PerformanceService
{
    /**
     * Ganancia diaria de peso por regresión lineal (mínimos cuadrados).
     * GDP = [n·Σ(d·p) − Σd·Σp] ÷ [n·Σd² − (Σd)²]
     */
    public function calculateGdp(Animal $animal): ?float
    {
        $points = [[0, $animal->entry_weight]];
        foreach ($animal->weighings as $w) {
            $points[] = [$animal->entry_date->diffInDays($w->weighed_at), (float) $w->weight_kg];
        }

        $n = count($points);
        if ($n < 2) {
            return null;
        }

        $sd = $sp = $sdd = $sdp = 0;
        foreach ($points as [$d, $p]) {
            $sd += $d; $sp += $p; $sdd += $d * $d; $sdp += $d * $p;
        }
        $den = $n * $sdd - $sd * $sd;

        return $den == 0 ? null : ($n * $sdp - $sd * $sp) / $den;
    }

    /** Etapa de engorde según el peso actual. */
    public function currentStage(Animal $animal): ?FeedingStage
    {
        return FeedingStage::forWeight($animal->currentWeight());
    }
}
