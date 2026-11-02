<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_whatsapp',
        'status',
        'payment_status',
        'payment_method',
        'payment_provider',
        'payment_reference',
        'payment_token',
        'payment_expires_at',
        'stock_released_at',
        'paid_at',
        'subtotal',
        'shipping_cost',
        'total',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
        'payment_expires_at' => 'datetime',
        'stock_released_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public static function statusOptions(): array
    {
        return [
            'pending' => 'Menunggu pembayaran',
            'processing' => 'Sedang disiapkan',
            'ready_for_pickup' => 'Siap diambil',
            'picked_up' => 'Sudah diambil',
            'canceled' => 'Dibatalkan',
        ];
    }

    public static function activeStatuses(): array
    {
        return ['pending', 'processing', 'ready_for_pickup'];
    }

    public static function completedStatuses(): array
    {
        return ['picked_up'];
    }

    public function statusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }

    public function trackingSteps(): array
    {
        $steps = [
            'pending' => ['label' => 'Pesanan diterima', 'description' => 'Pesanan sudah tercatat di sistem Agrinova.'],
            'processing' => ['label' => 'Sedang disiapkan', 'description' => 'Pesanan sedang disiapkan oleh tim Agrinova.'],
            'ready_for_pickup' => ['label' => 'Siap diambil', 'description' => 'Pesanan sudah siap diambil di lokasi Agrinova.'],
            'picked_up' => ['label' => 'Sudah diambil', 'description' => 'Pesanan telah diambil dan proses selesai.'],
        ];

        if ($this->status === 'canceled') {
            return [[
                'key' => 'canceled',
                'label' => 'Pesanan dibatalkan',
                'description' => 'Pesanan ini tidak lagi diproses.',
                'active' => true,
                'current' => true,
            ]];
        }

        $currentStatus = $this->status;
        $currentIndex = array_search($currentStatus, array_keys($steps), true);
        $currentIndex = $currentIndex === false ? 0 : $currentIndex;

        $trackingSteps = [];

        foreach (array_keys($steps) as $index => $key) {
            $trackingSteps[] = [
                'key' => $key,
                'label' => $steps[$key]['label'],
                'description' => $steps[$key]['description'],
                'active' => $index <= $currentIndex,
                'current' => $key === $currentStatus,
            ];
        }

        return $trackingSteps;
    }
}
