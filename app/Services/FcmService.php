<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\User;
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
    public function sendToUser($userId, $title, $body, $data = [])
    {
        $user = User::find($userId);

        if (!$user || !$user->fcm_token) {
            Log::warning("FCM: User {$userId} has no token registered");
            return false;
        }

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
    public function sendDataOnly($userId, $data = [])
    {
        $user = User::find($userId);

        if (!$user || !$user->fcm_token) {
            return false;
        }

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
}
