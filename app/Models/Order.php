<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'notes', 'subtotal', 'total',
        'payment_method', 'payment_status', 'payment_id', 'payment_order_id',
        'status', 'admin_notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'total'    => 'decimal:2',
    ];

    public static function generateOrderNumber(): string
    {
        return 'JEM-' . strtoupper(Str::random(8));
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'badge--gold',
            'processing' => 'badge--blue',
            'shipped'    => 'badge--green',
            'delivered'  => 'badge--gray',
            'cancelled'  => 'badge--red',
            default      => 'badge--gray',
        };
    }

    public function getPaymentBadgeAttribute(): string
    {
        return match($this->payment_status) {
            'pending'  => 'badge--gold',
            'paid'     => 'badge--green',
            'failed'   => 'badge--red',
            'refunded' => 'badge--gray',
            default    => 'badge--gray',
        };
    }
}
