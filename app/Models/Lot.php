<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lot extends Model
{
    protected $fillable = ['code', 'name', 'capacity', 'feed_id'];

    public function feed(): BelongsTo
    {
        return $this->belongsTo(Feed::class);
    }

    public function animals(): HasMany
    {
        return $this->hasMany(Animal::class);
    }

    /** Animales que siguen en el corral (no vendidos ni dados de baja). */
    public function activeAnimals(): HasMany
    {
        return $this->animals()->whereIn('status', [Animal::STATUS_FATTENING, Animal::STATUS_READY]);
    }
}
