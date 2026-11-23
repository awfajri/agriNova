<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

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
        'pickup_time',
        'pickup_date',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
        'payment_expires_at' => 'datetime',
        'stock_released_at' => 'datetime',
        'pickup_date' => 'date',
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

    /**
     * Slot jam pengambilan di kebun (jam operasional 08.00 - 16.00 WIB).
     */
    public static function pickupTimeOptions(): array
    {
        return [
            '08.00' => '08.00 WIB',
            '09.00' => '09.00 WIB',
            '10.00' => '10.00 WIB',
            '11.00' => '11.00 WIB',
            '12.00' => '12.00 WIB',
            '13.00' => '13.00 WIB',
            '14.00' => '14.00 WIB',
            '15.00' => '15.00 WIB',
            '16.00' => '16.00 WIB',
        ];
    }

    /**
     * Label untuk nilai lama yang sudah tidak dipakai lagi (kompatibilitas data).
     */
    protected const LEGACY_PICKUP_LABELS = [
        'fleksibel' => 'Fleksibel (konfirmasi via WhatsApp)',
    ];

    /**
     * Batas pemesanan tanggal ambil: hari ini s/d 30 hari ke depan.
     */
    public static function pickupDateMaxDays(): int
    {
        return 30;
    }

    /**
     * Zona waktu acuan jadwal ambil kebun (slot memakai WIB).
     */
    public static function pickupTimezone(): string
    {
        return 'Asia/Jakarta';
    }

    /**
     * Waktu sekarang pada zona waktu jadwal ambil.
     */
    public static function pickupNow(): Carbon
    {
        return now(self::pickupTimezone());
    }

    /**
     * Ubah slot "08.30" menjadi jumlah menit sejak tengah malam.
     */
    private static function slotToMinutes(string $slot): int
    {
        [$hour, $minute] = array_pad(explode('.', $slot), 2, '0');

        return ((int) $hour * 60) + (int) $minute;
    }

    /**
     * Slot jam yang masih bisa dipilih untuk tanggal tertentu.
     * Tanggal selain hari ini (WIB) selalu mengembalikan semua slot.
     *
     * @return list<string>
     */
    public static function availablePickupTimes(?string $date = null): array
    {
        $slots = array_keys(self::pickupTimeOptions());
        $now = self::pickupNow();

        if ($date === null || $date !== $now->toDateString()) {
            return $slots;
        }

        $nowMinutes = ($now->hour * 60) + $now->minute;

        return array_values(array_filter(
            $slots,
            fn (string $slot): bool => self::slotToMinutes($slot) >= $nowMinutes
        ));
    }

    /**
     * Apakah kombinasi tanggal + jam masih boleh dipesan?
     */
    public static function isPickupSlotAvailable(?string $date, ?string $time): bool
    {
        if (! $date || ! $time) {
            return false;
        }

        return in_array($time, self::availablePickupTimes($date), true);
    }

    public function pickupTimeLabel(): ?string
    {
        if (! $this->pickup_time) {
            return null;
        }

        return self::pickupTimeOptions()[$this->pickup_time]
            ?? self::LEGACY_PICKUP_LABELS[$this->pickup_time]
            ?? $this->pickup_time;
    }

    /**
     * Label tanggal ambil, contoh: "Sen, 23 Nov 2026".
     */
    public function pickupDateLabel(): ?string
    {
        if (! $this->pickup_date) {
            return null;
        }

        return $this->pickup_date->locale('id')->isoFormat('ddd, D MMM Y');
    }

    /**
     * Ringkasan jadwal ambil, contoh: "Sen, 23 Nov 2026 · 08.00 WIB".
     */
    public function pickupScheduleLabel(): ?string
    {
        $parts = array_filter([$this->pickupDateLabel(), $this->pickupTimeLabel()]);

        return $parts === [] ? null : implode(' · ', $parts);
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
