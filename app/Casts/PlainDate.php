<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Carbon;

/**
 * Columna DATE: se lee como Carbon y se guarda como "Y-m-d" (sin hora) en
 * cualquier motor (MySQL o SQLite), para que las validaciones por fecha
 * (por ejemplo, un solo pesaje por día) comparen correctamente.
 */
class PlainDate implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return $value === null ? null : Carbon::parse($value)->startOfDay();
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return ($value === null || $value === '') ? null : Carbon::parse($value)->toDateString();
    }
}
