<?php

namespace App\Models;

use App\Casts\PlainDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Treatment extends Model
{
    protected $fillable = ['diagnosis', 'dose_applied', 'cost', 'applied_at', 'withdrawal_until', 'animal_id', 'medicine_id', 'user_id'];

    protected $casts = ['applied_at' => PlainDate::class, 'withdrawal_until' => PlainDate::class, 'dose_applied' => 'float', 'cost' => 'float'];

    public function animal(): BelongsTo { return $this->belongsTo(Animal::class); }
    public function medicine(): BelongsTo { return $this->belongsTo(Medicine::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
