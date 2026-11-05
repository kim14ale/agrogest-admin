<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedingStage extends Model
{
    protected $fillable = ['name', 'min_weight', 'max_weight', 'intake_pct', 'expected_gdp', 'target_fca'];

    protected $casts = [
        'min_weight' => 'float', 'max_weight' => 'float', 'intake_pct' => 'float',
        'expected_gdp' => 'float', 'target_fca' => 'float',
    ];

    /** Devuelve la etapa que corresponde a un peso (el Core la usa para la ración). */
    public static function forWeight(float $weight): ?self
    {
        return static::where('min_weight', '<=', $weight)
            ->where('max_weight', '>', $weight)
            ->first();
    }
}
