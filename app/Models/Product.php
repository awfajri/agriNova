<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    private const DEFAULT_IMAGES = [
        'melon-premium' => '/images/products/melon-premium.svg',
        'sayur-campur-segar' => '/images/products/sayur-campur-segar.svg',
        'set-alat-kebun-dasar' => '/images/products/set-alat-kebun-dasar.svg',
        'pupuk-organik-cair' => '/images/products/pupuk-organik-cair.svg',
    ];

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'price',
        'stock',
        'min_order_quantity',
        'is_preorder',
        'is_featured',
        'is_active',
        'image_url',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'min_order_quantity' => 'integer',
        'is_preorder' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute(?string $value): ?string
    {
        if ($value && (!str_starts_with($value, '/') || is_file(public_path(ltrim($value, '/'))))) {
            return $value;
        }

        return self::DEFAULT_IMAGES[$this->slug] ?? null;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function preOrders(): HasMany
    {
        return $this->hasMany(PreOrder::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }
}
