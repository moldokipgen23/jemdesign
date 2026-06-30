<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'display_style',
        'items_per_row',
        'filter_value',
        'sort_order',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'items_per_row' => 'integer',
        'sort_order' => 'integer',
        'filter_value' => 'integer',
    ];

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true)->orderBy('sort_order');
    }

    public function items(): HasMany
    {
        return $this->hasMany(MarketingSectionItem::class)->orderBy('sort_order');
    }

    /**
     * Get products for this section based on type and filters
     */
    public function getProducts()
    {
        $query = Product::with(['colors', 'colors.images', 'variants']);

        switch ($this->type) {
            case 'trending':
                $query->where('is_featured', true)->latest();
                break;

            case 'new_arrivals':
                $query->latest();
                break;

            case 'best_selling':
                // Order by order count (simplified - just get all products)
                $query->latest();
                break;

            case 'category':
                if ($this->filter_value) {
                    $query->where('category_id', $this->filter_value);
                }
                $query->latest();
                break;

            case 'collection':
                if ($this->filter_value) {
                    $query->whereHas('collections', fn($q) => $q->where('collections.id', $this->filter_value));
                }
                $query->latest();
                break;

            case 'manual':
                return $this->items()
                    ->where('itemable_type', Product::class)
                    ->with('itemable')
                    ->get()
                    ->pluck('itemable');

            default:
                $query->latest();
        }

        return $query->limit($this->items_per_row * 2)->get();
    }

    /**
     * Get testimonials for this section
     */
    public function getTestimonials()
    {
        if ($this->type === 'manual') {
            return $this->items()
                ->where('itemable_type', Testimonial::class)
                ->with('itemable')
                ->get()
                ->pluck('itemable');
        }

        return Testimonial::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
}
