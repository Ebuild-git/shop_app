<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdersItem extends Model
{
    use HasFactory;

    protected $table = 'orders_items';

    protected $fillable = [
        'order_id',
        'post_id',
        'vendor_id',
        'price',
        'delivery_fee',
        'status', 'shipment_id'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id')->withTrashed();
    }


    public function post(): BelongsTo
    {
        return $this->belongsTo(posts::class, 'post_id');
    }

    public function calculateGain(): float
    {
        return $this->post ? $this->post->calculateGain() : 0;
    }
}
