<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EduwisataReservation extends Model
{
    protected $fillable = [
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

    public function statusLabel(): string
    {
        return match ($this->status) {
            'confirmed' => 'Dikonfirmasi',
            'rejected' => 'Ditolak',
            default => 'Menunggu',
        };
    }
}
