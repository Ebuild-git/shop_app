<?php

namespace App\Http\Controllers;

use App\Models\notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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


    public function delete_notification(Request $request)
    {
        $id = $request->input('id_notification' ?? null);
        try {
            $notification = notifications::where("id", $id)
                ->where("id_user_destination", Auth::user()->id)
                ->first();
            if ($notification) {
                $notification->delete();
                return response()->json(
                    [
                        'success' => true,
                        'message' => __('notification_deleted'),
                    ]
                );
            }
        } catch (\Exception $exception) {
            return response()->json(
                [
                    'success' => false,
                    'message' =>  "Impossible de trouver la notification"
                ]
            );
        }
    }


    public function delete_all(){
        $notification = notifications::where("id_user_destination", Auth::user()->id)
                ->delete();
                return redirect()->route('user-notifications');

    }

    public function user_notifications() {
        $user_id = Auth::id();

        notifications::where("id_user_destination", $user_id)
            ->where("statut", "unread")
            ->update(["statut" => "read"]);

        $notifications = notifications::where("id_user_destination", $user_id)
            ->orderBy("id", "desc")
            ->get();

        $unreadCount = 0;

        return view('User.notifications', compact("notifications", "unreadCount"));
    }

    public function count_notification() {
        $count = notifications::where("id_user_destination", Auth::id())
                              ->where("statut", "unread")
                              ->count();
        return response()->json([
            'statut' => true,
            'count' => $count
        ]);
    }

    public function destroy($id)
    {
        try {
            $notif = notifications::where('id', $id)
                ->where('destination', 'admin')
                ->firstOrFail();

            $notif->delete();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
