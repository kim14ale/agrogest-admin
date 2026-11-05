<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicine extends Model
{
    public const TYPES = ['Antibiótico', 'Antiinflamatorio', 'Antiparasitario', 'Vitamina', 'Vacuna'];

    /** Peso de cada tipo en el Índice de Carga Sanitaria (ICS). */
    public const HEALTH_WEIGHTS = [
        'Antibiótico' => 3, 'Antiinflamatorio' => 2, 'Antiparasitario' => 1, 'Vitamina' => 0.5, 'Vacuna' => 0.5,
    ];

    protected $fillable = ['name', 'type', 'dose_per_kg', 'max_dose', 'min_interval_days', 'withdrawal_days', 'unit_cost', 'stock'];

    protected $casts = ['dose_per_kg' => 'float', 'max_dose' => 'float', 'unit_cost' => 'float', 'stock' => 'float'];

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }

    public function healthWeight(): float
    {
        return self::HEALTH_WEIGHTS[$this->type] ?? 1;
    }
}
