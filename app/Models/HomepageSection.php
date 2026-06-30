<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class HomepageSection extends Model
{
    protected $fillable = ['section_key', 'image_path', 'is_enabled', 'sort_order'];

    protected $casts = ['is_enabled' => 'boolean'];

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true)->orderBy('sort_order');
    }
}
