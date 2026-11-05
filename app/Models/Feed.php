<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feed extends Model
{
    protected $fillable = ['name', 'dry_matter_pct', 'cost_per_kg', 'stock_kg'];

    protected $casts = ['dry_matter_pct' => 'float', 'cost_per_kg' => 'float', 'stock_kg' => 'float'];

    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class);
    }

    public function costPerKgDryMatter(): float
    {
        return $this->cost_per_kg / ($this->dry_matter_pct / 100);
    }
}
