<?php

namespace App\Models;

use App\Casts\PlainDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Weighing extends Model
{
    protected $fillable = ['weight_kg', 'weighed_at', 'animal_id', 'user_id'];

    protected $casts = ['weighed_at' => PlainDate::class, 'weight_kg' => 'float'];

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
