<?php

namespace App\Traits;

trait ShipmentStatusTrait
{

    public static function getShipmentStatus($updateCode)
    {
        return match ($updateCode) {
            'SH001', 'SH014', 'SH203' => 'created',
            'SH012' => 'picked_up',
            'SH003', 'SH004', 'SH073', 'SH252' => 'out_for_delivery',
            'SH005', 'SH006', 'SH007', 'SH154', 'SH234', 'SH496' => 'delivered',
            'SH006' => 'attempted_delivery',
            'SH076' => 'delayed',
            'SH008' => 'returned',
            'SH033', 'SH043', 'SH294', 'SH480' => 'not_delivered',
            default => $updateCode,
        };
    }

}
