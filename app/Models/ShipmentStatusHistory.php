<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'post_id',
        'order_item_id',
        'old_etat',
        'new_etat',
        'update_code',
        'update_description',
        'update_location',
        'update_datetime',
    ];

    public function post()
    {
        return $this->belongsTo(posts::class, 'post_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrdersItem::class, 'order_item_id');
    }
}
