<?php

namespace App\Services;

use App\Models\User;
use App\Models\notifications;
use Illuminate\Support\Facades\Log;

class FcmNotificationService
{
    public function sendCountUpdate(User $user, $notification = null)
    {
        try {
            if (!$user->fcm_token) {
                Log::warning("FCM: User {$user->id} has no token registered");
                return false;
            }

            // Small delay to ensure notification is persisted in DB first
            $unreadCount = notifications::where('id_user_destination', $user->id)
                ->where('statut', 'unread')
                ->count();

            $totalCount = notifications::where('id_user_destination', $user->id)
                ->count();

            // Get the actual notifications model ID, not the Laravel notification class
            $notificationId = notifications::where('id_user_destination', $user->id)
                ->latest()
                ->value('id');

            app(FcmService::class)->sendDataOnly($user->id, [
                'type'            => 'notification_count_update',
                'unread_count'    => (string) $unreadCount,
                'total_count'     => (string) $totalCount,
                'notification_id' => (string) ($notificationId ?? ''),
            ]);

            Log::info('FCM notification count update sent', [
                'user_id'         => $user->id,
                'unread_count'    => $unreadCount,
                'total_count'     => $totalCount,
                'notification_id' => $notificationId,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send FCM notification count update', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
