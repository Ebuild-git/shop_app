<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\User;
use App\Models\notifications;
use Illuminate\Support\Facades\Log;

class FcmService
{
    protected $messaging;

    public function __construct()
    {
        $firebase = (new Factory)
            ->withServiceAccount(config('services.firebase.credentials'));

        $this->messaging = $firebase->createMessaging();
    }

    /**
     * Send notification to a specific user by ID
     * This is similar to Pusher Beams - you just need the user ID
     */
    // public function sendToUser($userId, $title, $body, $data = [])
    // {
    //     $title = strip_tags($title);
    //     $body  = strip_tags($body);

    //     $user = User::find($userId);

    //     if (!$user || !$user->fcm_token) {
    //         Log::warning("FCM: User {$userId} has no token registered");
    //         return false;
    //     }

    //     return $this->sendToToken($user->fcm_token, $title, $body, $data);
    // }
    public function sendToUser($userId, $title, $body, $data = [], $notification = null)
    {
        $title = strip_tags($title);
        $body  = strip_tags($body);

        $user = User::find($userId);

        if (!$user || !$user->fcm_token) {
            Log::warning("FCM: User {$userId} has no token registered");
            return false;
        }

        $data['unread_count']    = notifications::where('id_user_destination', $userId)->where('statut', 'unread')->count();
        $data['total_count']     = notifications::where('id_user_destination', $userId)->count();
        $data['notification_id'] = $notification?->id;

        return $this->sendToToken($user->fcm_token, $title, $body, $data);
    }

    /**
     * Send notification directly to a token
     */
    public function sendToToken($token, $title, $body, $data = [])
    {
        try {
            $message = CloudMessage::withTarget('token', $token)
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            $this->messaging->send($message);
            Log::info("FCM: Notification sent successfully");
            return true;
        } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
            // Token is invalid or expired - remove it
            Log::warning("FCM: Invalid token, removing from database");
            User::where('fcm_token', $token)->update(['fcm_token' => null]);
            return false;
        } catch (\Exception $e) {
            Log::error('FCM Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send to multiple users
     */
    public function sendToMultipleUsers($userIds, $title, $body, $data = [])
    {
        $tokens = User::whereIn('id', $userIds)
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->toArray();

        if (empty($tokens)) {
            Log::warning("FCM: No valid tokens found for users");
            return false;
        }

        return $this->sendToMultipleTokens($tokens, $title, $body, $data);
    }

    /**
     * Send to multiple tokens
     */
    public function sendToMultipleTokens($tokens, $title, $body, $data = [])
    {
        try {
            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            $report = $this->messaging->sendMulticast($message, $tokens);

            Log::info("FCM: Sent to " . $report->successes()->count() . " devices");

            // Handle invalid tokens
            if ($report->hasFailures()) {
                foreach ($report->failures()->getItems() as $failure) {
                    $invalidToken = $failure->target()->value();
                    User::where('fcm_token', $invalidToken)->update(['fcm_token' => null]);
                }
            }

            return true;
        } catch (\Exception $e) {
            Log::error('FCM Multicast Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send data-only notification (silent, no popup)
     */
    // public function sendDataOnly($userId, $data = [])
    // {
    //     $user = User::find($userId);

    //     if (!$user || !$user->fcm_token) {
    //         return false;
    //     }

    //     try {
    //         $message = CloudMessage::withTarget('token', $user->fcm_token)
    //             ->withData($data);

    //         $this->messaging->send($message);
    //         return true;
    //     } catch (\Exception $e) {
    //         Log::error('FCM Error: ' . $e->getMessage());
    //         return false;
    //     }
    // }
    public function sendDataOnly($userId, $data = [], $notification = null)
    {
        $user = User::find($userId);

        if (!$user || !$user->fcm_token) {
            return false;
        }

        $data['unread_count']    = notifications::where('id_user_destination', $userId)->where('statut', 'unread')->count();
        $data['total_count']     = notifications::where('id_user_destination', $userId)->count();
        $data['notification_id'] = $notification?->id;

        try {
            $message = CloudMessage::withTarget('token', $user->fcm_token)
                ->withData($data);

            $this->messaging->send($message);
            return true;
        } catch (\Exception $e) {
            Log::error('FCM Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification with real-time unread count badge
     * @param int $userId User ID
     * @param string $title Notification title
     * @param string $body Notification body
     * @param array $data Additional data to include
     * @return bool
     */
    public function sendNotificationWithBadge($userId, $title, $body, $data = [])
    {
        $user = User::find($userId);

        if (!$user || !$user->fcm_token) {
            Log::warning("FCM: User {$userId} has no token registered");
            return false;
        }

        $unreadCount = \App\Models\notifications::where('id_user_destination', $userId)
            ->where('statut', 'unread')
            ->count();

        $data['unread_count'] = $unreadCount;

        return $this->sendToToken($user->fcm_token, $title, $body, $data);
    }

    /**
     * Send silent badge update to multiple users
     * @param array $userIds Array of user IDs
     * @return bool
     */
    public function sendBadgeUpdate($userIds)
    {
        $tokens = User::whereIn('id', $userIds)
            ->whereNotNull('fcm_token')
            ->get(['id', 'fcm_token']);

        $messages = [];

        foreach ($tokens as $token) {
            $unreadCount = notifications::where('id_user_destination', $token->id)
                ->where('statut', 'unread')
                ->count();

            $message = CloudMessage::withTarget('token', $token->fcm_token)
                ->withData(['type' => 'badge_update', 'unread_count' => $unreadCount]);

            $messages[] = $message;
        }

        try {
            foreach ($messages as $message) {
                $this->messaging->send($message);
            }
            return true;
        } catch (\Exception $e) {
            Log::error('FCM Badge Update Error: ' . $e->getMessage());
            return false;
        }
    }
}
