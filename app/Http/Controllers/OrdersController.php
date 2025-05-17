<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AramexService;
use App\Models\Shipment;

class OrdersController extends Controller
{
    public function myOrders(){
        return view('User.orders');
    }
}
