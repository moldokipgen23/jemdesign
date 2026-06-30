<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MarketingSectionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'marketing_section_id',
        'itemable_type',
        'itemable_id',
        'sort_order',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(MarketingSection::class, 'marketing_section_id');
    }

    public function itemable(): MorphTo
    {
        return $this->morphTo();
    }
}
