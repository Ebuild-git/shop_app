<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'shipment_id',
        'buyer_id',
        'total',
        'total_delivery_fees',
        'status',
        'state',
    ];


    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id')->withTrashed();
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrdersItem::class, 'order_id');
    }

    public function uniqueVendorsCount(): int
    {
        return $this->items()->distinct('vendor_id')->count('vendor_id');
    }
}
