<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'customer_name', 'customer_phone', 'cart_summary', 'total_estimate', 'status',
    ];

    protected $casts = [
        'cart_summary'   => 'array',
        'total_estimate' => 'decimal:2',
    ];

    public function getItemCountAttribute(): int
    {
        return collect($this->cart_summary)->sum('qty');
    }
}
