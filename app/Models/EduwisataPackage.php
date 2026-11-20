<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EduwisataPackage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'short_title',
        'tag',
        'price',
        'quota',
        'subtitle',
        'summary',
        'image',
        'highlights',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price' => 'integer',
        'highlights' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function toPublicArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'short_title' => $this->short_title,
            'tag' => $this->tag,
            'price' => $this->price,
            'quota' => $this->quota,
            'subtitle' => $this->subtitle,
            'summary' => $this->summary,
            'image' => $this->image ?: 'images/edukasi2.webp',
            'highlights' => is_array($this->highlights) ? $this->highlights : [],
            'is_active' => $this->is_active,
        ];
    }
}