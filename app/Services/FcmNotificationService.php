<?php

namespace App\Services;

use App\Models\FcmToken;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class FcmNotificationService
{
    public function sendCountUpdate(User $user, $notification = null)
    {
        try {
            $hasTokens = FcmToken::where('user_id', $user->id)->exists();

            if (! $hasTokens) {
                Log::warning("FCM: User {$user->id} has no tokens registered");

                return false;
            }

            $unreadCount = $user->unreadNotifications()->count();
            $totalCount = $user->notifications()->count();

            app(FcmService::class)->sendDataOnly($user->id, [
                'type' => 'notification_count_update',
                'unread_count' => $unreadCount,
                'total_count' => $totalCount,
                'notification_id' => $notification?->id,
            ]);

            Log::info('FCM notification count update sent', [
                'user_id' => $user->id,
                'unread_count' => $unreadCount,
                'total_count' => $totalCount,
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
