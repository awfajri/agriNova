<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingClass extends Model
{
    protected $fillable = [
        'slug',
        'category',
        'title',
        'price',
        'duration',
        'schedule',
        'capacity',
        'status',
        'image',
        'highlights',
        'summary',
        'description',
        'materials',
        'is_available',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'capacity' => 'integer',
        'highlights' => 'array',
        'materials' => 'array',
        'is_available' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function registrations(): HasMany
    {
        return $this->hasMany(TrainingRegistration::class);
    }

    public function toPublicArray(): array
    {
        return [
            'slug' => $this->slug,
            'kategori' => $this->category,
            'judul' => $this->title,
            'harga' => (float) $this->price,
            'durasi' => $this->duration,
            'jadwal' => $this->schedule,
            'kuota' => $this->capacity > 0 ? "Sisa {$this->capacity} Kursi" : 'Kuota Penuh',
            'status' => $this->status,
            'is_available' => $this->is_available,
            'image' => $this->image,
            'highlight' => $this->highlights ?? [],
            'ringkasan' => $this->summary,
            'deskripsi' => $this->description,
            'materi' => $this->materials ?? [],
        ];
    }
}
