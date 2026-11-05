<?php

namespace App\Models;

use App\Casts\PlainDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feeding extends Model
{
    protected $fillable = ['quantity_kg', 'fed_at', 'lot_id', 'feed_id', 'user_id'];

    protected $casts = ['fed_at' => PlainDate::class, 'quantity_kg' => 'float'];

    public function lot(): BelongsTo { return $this->belongsTo(Lot::class); }
    public function feed(): BelongsTo { return $this->belongsTo(Feed::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
