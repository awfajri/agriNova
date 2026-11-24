<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'eyebrow',
        'image_path',
        'cta_text',
        'cta_url',
        'order',
        'is_active',
        'is_countdown',
        'countdown_end_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_countdown' => 'boolean',
        'countdown_end_at' => 'datetime',
        'order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('order')->orderByDesc('id');
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return asset('images/banner-1.jpg');
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        return asset($this->image_path);
    }
}
