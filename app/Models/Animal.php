<?php

namespace App\Models;

use App\Casts\PlainDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Animal extends Model
{
    public const STATUS_FATTENING = 'En engorde';
    public const STATUS_READY = 'Listo para venta';
    public const STATUS_SOLD = 'Vendido';
    public const STATUS_DOWN = 'Baja';
    public const STATUSES = [self::STATUS_FATTENING, self::STATUS_READY, self::STATUS_SOLD, self::STATUS_DOWN];
    public const SEXES = ['Macho', 'Hembra'];

    protected $fillable = [
        'tag_number', 'sex', 'birth_date', 'entry_date', 'entry_weight', 'purchase_cost',
        'target_weight', 'status', 'breed_id', 'lot_id',
    ];

    protected $casts = [
        'birth_date' => PlainDate::class, 'entry_date' => PlainDate::class,
        'entry_weight' => 'float', 'purchase_cost' => 'float', 'target_weight' => 'float',
    ];

    public function breed(): BelongsTo
    {
        return $this->belongsTo(Breed::class);
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    public function weighings(): HasMany
    {
        return $this->hasMany(Weighing::class)->orderBy('weighed_at');
    }

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }

    /** Último peso registrado (o el peso de ingreso si aún no tiene pesajes). */
    public function currentWeight(): float
    {
        $last = $this->weighings()->reorder('weighed_at', 'desc')->first();

        return $last ? (float) $last->weight_kg : $this->entry_weight;
    }

    public function lastWeighingDate()
    {
        return $this->weighings()->reorder('weighed_at', 'desc')->value('weighed_at') ?? $this->entry_date;
    }
}
