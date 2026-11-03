<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingRegistration extends Model
{
    protected $fillable = [
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

    public function trainingClass(): BelongsTo
    {
        return $this->belongsTo(TrainingClass::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'confirmed' => 'Dikonfirmasi',
            'rejected' => 'Ditolak',
            default => 'Menunggu',
        };
    }
}
