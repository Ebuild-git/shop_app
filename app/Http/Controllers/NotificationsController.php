<?php

namespace App\Http\Controllers;

use App\Models\notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user/notifications",
     *     summary="Get user notifications",
     *     description="Returns all notifications for the authenticated user with unread count",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Notifications retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="unread_count", type="integer", example=5),
     *             @OA\Property(property="notifications", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function user_notifications_api(Request $request)
    {
        $user_id = $request->user()->id;

        $notifications = notifications::where("id_user_destination", $user_id)
            ->orderBy("id", "desc")
            ->get();

        $unreadCount = notifications::where("id_user_destination", $user_id)
            ->where("statut", "unread")
            ->count();

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
            'notifications' => $notifications
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/notifications/unread-count",
     *     summary="Get unread notification count",
     *     description="Returns the count of unread notifications for the authenticated user",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Unread count retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="unread_count", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function get_unread_count(Request $request)
    {
        $user_id = $request->user()->id;

        $unreadCount = notifications::where("id_user_destination", $user_id)
            ->where("statut", "unread")
            ->count();

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/notifications/mark-read/{id}",
     *     summary="Mark notification as read",
     *     description="Marks a specific notification as read",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Notification marked as read",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Notification marked as read")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Notification not found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function mark_as_read_notification(Request $request, $id)
    {
        try {
            $notification = notifications::where('id', $id)
                ->where('id_user_destination', $request->user()->id)
                ->firstOrFail();
            $notification->update(["statut" => "read"]);
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Notification not found"
            ], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/notifications/mark-unread/{id}",
     *     summary="Mark notification as unread",
     *     description="Marks a specific notification as unread",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Notification marked as unread",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Notification marked as unread")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Notification not found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function mark_as_unread_notification(Request $request, $id)
    {
        try {
            $notification = notifications::where('id', $id)
                ->where('id_user_destination', $request->user()->id)
                ->firstOrFail();
            $notification->update(["statut" => "unread"]);
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as unread'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Notification not found"
            ], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/notifications/mark-all-read",
     *     summary="Mark all notifications as read",
     *     description="Marks all user notifications as read",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="All notifications marked as read",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="All notifications marked as read")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function mark_all_as_read(Request $request)
    {
        notifications::where("id_user_destination", $request->user()->id)
            ->where("statut", "unread")
            ->update(["statut" => "read"]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/notifications/mark-all-unread",
     *     summary="Mark all notifications as unread",
     *     description="Marks all user notifications as unread",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="All notifications marked as unread",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="All notifications marked as unread")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function mark_all_as_unread(Request $request)
    {
        notifications::where("id_user_destination", $request->user()->id)
            ->where("statut", "read")
            ->update(["statut" => "unread"]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as unread'
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/notifications/delete/{id}",
     *     summary="Delete notification",
     *     description="Deletes a specific notification",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Notification deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Notification not found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function delete_notification(Request $request, $id)
    {
        try {
            $notification = notifications::where("id", $id)
                ->where("id_user_destination", $request->user()->id)
                ->first();
            if ($notification) {
                $notification->delete();
                return response()->json([
                    'success' => true,
                    'message' => __('notification_deleted'),
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => "Notification not found"
            ], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Failed to delete notification"
            ], 500);
        }
    }

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


    public function delete_all(){
        $notification = notifications::where("id_user_destination", Auth::user()->id)
                ->delete();
                return redirect()->route('user-notifications');

    }

    // public function user_notifications() {
    //     $user_id = Auth::id();

    //     notifications::where("id_user_destination", $user_id)
    //         ->where("statut", "unread")
    //         ->update(["statut" => "read"]);

    //     $notifications = notifications::where("id_user_destination", $user_id)
    //         ->orderBy("id", "desc")
    //         ->get();

    //     $unreadCount = 0;

    //     return view('User.notifications', compact("notifications", "unreadCount"));
    // }

    public function user_notifications()
    {
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

    public function getNotificationsJson($userId)
    {
        abort_if(Auth::id() != $userId, 403);

        $notifications = notifications::where("id_user_destination", $userId)
            ->orderBy("id", "desc")
            ->take(20)
            ->get()
            ->map(function ($item) {
                return [
                    'id'       => $item->id,
                    'titre'    => $item->titre,
                    'message'  => $item->message,
                    'url'      => $item->url,
                    'statut'   => $item->statut,
                    'type'     => $item->type ?? 'default',
                    'time_ago' => \Carbon\Carbon::parse($item->created_at)->diffForHumans(),
                ];
            });

        return response()->json(['notifications' => $notifications]);
    }

    public function markAllRead($userId)
    {
        abort_if(Auth::id() != $userId, 403);

        notifications::where("id_user_destination", $userId)
            ->where("statut", "unread")
            ->update(["statut" => "read"]);

        return response()->json(['success' => true]);
    }

    public function deleteNotification($userId, $id)
    {
        abort_if(Auth::id() != $userId, 403);

        notifications::where("id", $id)
            ->where("id_user_destination", $userId)
            ->delete();

        return response()->json(['success' => true]);
    }

    // NotificationsController.php
    public function deleteAllNotifications($userId)
    {
        abort_if(Auth::id() != $userId, 403);

        notifications::where("id_user_destination", $userId)->delete();

        return response()->json(['success' => true]);
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
