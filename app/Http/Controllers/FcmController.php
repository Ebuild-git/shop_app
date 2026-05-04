<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FcmService;
use App\Models\notifications;

class FcmController extends Controller
{
    protected $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    /**
     * @OA\Post(
     *     path="/api/fcm/register-token",
     *     summary="Register FCM token",
     *     description="Registers the user's FCM token for push notifications",
     *     tags={"FCM"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="fcm_token", type="string", example="fcm_token_here")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="FCM token registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="FCM token registered successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function registerToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = $request->user();

        $user->update([
            'fcm_token' => $request->fcm_token,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'FCM token registered successfully'
        ]);
    }


    /**
     * @OA\Delete(
     *     path="/api/fcm/remove-token",
     *     summary="Remove FCM token",
     *     description="Removes the user's FCM token",
     *     tags={"FCM"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="FCM token removed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="FCM token removed successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function removeToken(Request $request)
    {
        $request->user()->update([
            'fcm_token' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'FCM token removed successfully'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/fcm/update-badge",
     *     summary="Get unread notification count",
     *     description="Returns the current unread notification count for badge display",
     *     tags={"FCM"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Unread count retrieved",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="unread_count", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function updateBadge(Request $request)
    {
        $user = $request->user();
        $unreadCount = notifications::where('id_user_destination', $user->id)
            ->where('statut', 'unread')
            ->count();

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount
        ]);
    }
}