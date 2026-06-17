<?php

namespace App\Traits;

trait ShipmentStatusTrait
{

    public static function getShipmentStatus($updateCode)
    {
        return match ($updateCode) {
            'SH001', 'SH014', 'SH203'                              => 'order_confirmed',
            'SH012', 'SH314'                                       => 'picked_up',
            'SH003', 'SH004'                                       => 'out_for_delivery',
            'SH033'                                                => 'attempted_delivery',
            'SH005', 'SH006', 'SH007',
            'SH154', 'SH234', 'SH496', 'SH597', 'SH239'           => 'delivered',
            'SH704'                                                => 'returned_to_shipper',
            'SH313'                                                => 'cancelled',
            'SH008', 'SH043', 'SH076'                             => 'delivery_delayed',
            'SH308'                                                => 'pickup_scheduled',
            'SH312'                                                => 'rescheduled',
            default                                                => $updateCode,
        };
    }

    public static function mapAramexToStatut($updateCode)
    {
        $status = self::getShipmentStatus($updateCode);

        return match ($status) {
            'order_confirmed'    => 'commande confirmée',
            'picked_up'          => 'ramassée',
            'out_for_delivery'   => 'en cours de livraison',
            'attempted_delivery' => 'tentative de livraison',
            'delivered'          => 'livré',
            'returned_to_shipper'=> 'retourné à l\'expéditeur',
            'cancelled'          => 'annulé',
            'delivery_delayed'   => 'livraison retardée',
            'pickup_scheduled'   => 'ramassage planifié',
            'rescheduled'        => 'reprogrammé',
            default              => 'commande confirmée',
        };
    }

}
