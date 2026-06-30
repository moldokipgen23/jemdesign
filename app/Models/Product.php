<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'type', 'cover_image', 'description', 'heritage_note',
        'price', 'sku', 'stock', 'material', 'weight', 'care_instructions',
        'is_top_seller', 'is_featured', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'price'         => 'decimal:2',
        'stock'         => 'integer',
        'is_top_seller' => 'boolean',
        'is_featured'   => 'boolean',
        'is_active'     => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'product_collections');
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute');
    }

    public function colors(): HasMany
    {
        return $this->hasMany(ProductColor::class)->orderBy('sort_order');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(ProductVideo::class)->orderBy('sort_order');
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(ProductSize::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeTopSellers(Builder $query): Builder
    {
        return $query->where('is_top_seller', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function getMainImageAttribute(): ?string
    {
        if ($this->cover_image) {
            return $this->cover_image;
        }
        // Use already-loaded relationship if available, otherwise query
        $colors = $this->colors ?? $this->colors()->with('images')->get();
        $firstColor = $colors->first();
        if (!$firstColor) return null;
        $images = $firstColor->images ?? $firstColor->images()->orderBy('sort_order')->get();
        return $images->first()?->image_path;
    }

    public function isVariable(): bool
    {
        return $this->type === 'variable';
    }
}
