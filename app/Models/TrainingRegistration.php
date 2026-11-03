<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TrainingRegistration extends Model
{
    protected $fillable = [
        'reference_number',
        'training_class_id',
        'class_title',
        'name',
        'whatsapp',
        'email',
        'institution',
        'participants',
        'notes',
        'status',
    ];

    protected $casts = [
        'participants' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $registration): void {
            $registration->reference_number ??= 'TRN-' . now()->format('ymd') . '-' . Str::upper(Str::random(6));
        });
    }

    public function trainingClass(): BelongsTo
    {
        return $this->belongsTo(TrainingClass::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'confirmed' => 'Dikonfirmasi',
            'rejected' => 'Ditolak',
            'completed' => 'Selesai',
            default => 'Menunggu',
        };
    }

    public function statusSteps(): array
    {
        return [
            ['key' => 'pending', 'label' => 'Pendaftaran diterima', 'description' => 'Data kamu sudah tercatat dan menunggu ditinjau admin.'],
            ['key' => 'confirmed', 'label' => 'Pendaftaran dikonfirmasi', 'description' => 'Admin sudah mengonfirmasi kelas dan detail keikutsertaanmu.'],
            ['key' => 'completed', 'label' => 'Pelatihan selesai', 'description' => 'Tahap ini akan aktif setelah kegiatan pelatihan selesai.'],
        ];
    }
}
