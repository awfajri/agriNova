<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EduwisataReservation extends Model
{
    protected $fillable = [
        'reference_number',
        'contact_name',
        'group_name',
        'package_name',
        'whatsapp',
        'visit_date',
        'session',
        'participants',
        'notes',
        'status',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'participants' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $reservation): void {
            $reservation->reference_number ??= 'EDU-' . now()->format('ymd') . '-' . Str::upper(Str::random(6));
        });
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'confirmed' => 'Dikonfirmasi',
            'rejected' => 'Ditolak',
            'completed' => 'Kunjungan selesai',
            default => 'Menunggu',
        };
    }

    public function statusSteps(): array
    {
        return [
            ['key' => 'pending', 'label' => 'Reservasi diterima', 'description' => 'Permintaan kunjunganmu sudah tercatat dan menunggu konfirmasi admin.'],
            ['key' => 'confirmed', 'label' => 'Jadwal dikonfirmasi', 'description' => 'Admin sudah mengonfirmasi jadwal, sesi, dan kapasitas rombongan.'],
            ['key' => 'completed', 'label' => 'Kunjungan selesai', 'description' => 'Tahap ini akan aktif setelah rombongan selesai berkunjung.'],
        ];
    }
}
