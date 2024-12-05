<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'client_info',
        'reference1',
        'reference2',
        'reference3',
        'reference4',
        'reference5',
        'shipment_details',
        'origin',
        'destination',
        'status',
        'notifications',
        'tracking_number',
        'request_data',
        'response_data',
        'additional_details'
    ];

    protected $casts = [
        'client_info' => 'array',
        'shipment_details' => 'array',
        'notifications' => 'array',
        'request_data' => 'array',
        'response_data' => 'array',
        'additional_details' => 'array'
    ];
}
