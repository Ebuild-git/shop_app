<?php

namespace App\Http\Controllers;

use App\Models\notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationsController extends Controller
{
    public function list_notification()
    {
        $notifications = notifications::Orderby("id", "desc")->get();
        return response()->json(
            [
                'success' => true,
                'data' => $notifications
            ]
        );
    }


    public function mark_as_read_notification($id)
    {
        try {
            $notification = notifications::findOrFail($id);
            $notification->update(["statut" => "read"]);
            return response()->json(
                [
                    'success' => true,
                    'message' => 'La notification a été marqué comme lu',
                ]
            );
        } catch (\Exception $exception) {
            return response()->json(
                [
                    'success' => false,
                    'message' =>  "Impossible de trouver la notification"
                ]
            );
        }
    }


    public function delete_notification($id)
    {
        try {
            $notification = notifications::findOrFail($id);
            $notification->delete();
            return response()->json(
                [
                    'success' => true,
                    'message' => 'notification a été supprimé',
                ]
            );
        } catch (\Exception $exception) {
            return response()->json(
                [
                    'success' => false,
                    'message' =>  "Impossible de trouver la notification"
                ]
            );
        }
    }



}
