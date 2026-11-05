<?php

namespace App\Models;

use App\Casts\PlainDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    protected $fillable = ['code', 'buyer_name', 'buyer_id_number', 'weight_kg', 'price_per_kg', 'total', 'sold_at', 'animal_id', 'user_id'];

    protected $casts = ['sold_at' => PlainDate::class];

    public function animal(): BelongsTo { return $this->belongsTo(Animal::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
