<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreOrder extends Model
{
    protected $table = 'preorders';

    protected $fillable = [
        'product_id',
        'title',
        'slug',
        'harvest_date',
        'booking_open_at',
        'booking_close_at',
        'slot_available',
        'slot_booked',
        'is_active',
        'description',
    ];

    protected $casts = [
        'harvest_date' => 'date',
        'booking_open_at' => 'datetime',
        'booking_close_at' => 'datetime',
        'slot_available' => 'integer',
        'slot_booked' => 'integer',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
