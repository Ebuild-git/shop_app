<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsletterSubscription;
use Illuminate\Support\Facades\Validator;
use App\Mail\WelcomeNewsletterMail;
use Illuminate\Support\Facades\Mail; // Add this line
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'name' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }
        try {
            $subscription = NewsletterSubscription::updateOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name ?? $request->email,
                    'consent_rgpd' => true,
                    'consent_rgpd_at' => now(),
                    'consent_text' => __('newsletter_consent_text'),
                    'source' => $request->source ?? 'website_popup',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'active' => true,
                    'subscribed_at' => now(),
                ]
            );
            // Send welcome email
            Mail::to($request->email)->send(new WelcomeNewsletterMail($subscription));

            if (count(Mail::failures()) === 0) {
            Log::info('Newsletter welcome email sent successfully', [
                'email' => $request->email,
                'subscription_id' => $subscription->id,
                'ip' => $request->ip(),
            ]);
        } else {
            Log::warning('Newsletter email failed to send', [
                'email' => $request->email,
                'failures' => Mail::failures(),
            ]);
        }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('newsletter_subscribed_success')
                ]);
            }
            return back()->with('success', __('newsletter_subscribed_success'));
        } catch (\Exception $e) {
            Log::error('Newsletter subscription error', [
            'email' => $request->email ?? null,
            'error' => $e->getMessage(),
        ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('newsletter_subscription_error')
                ], 500);
            }
            return back()->with('error', __('newsletter_subscription_error'));
        }
    }

    public function unsubscribe($token)
    {
        $subscription = NewsletterSubscription::where('unsubscribe_token', $token)->first();
        if (!$subscription) {
            return redirect('/')->with('error', __('newsletter_invalid_token'));
        }
        $subscription->unsubscribe();
        return view('User.newsletter-unsubscribed')->with('success', __('newsletter_unsubscribed_success'));
    }
    public function updatePreferences(Request $request)
    {
        $user = auth()->user();

        $subscription = NewsletterSubscription::where('email', $user->email)->first();
        if ($request->has('subscribe') && $request->subscribe) {
            if ($subscription) {
                $subscription->resubscribe();
            } else {
                NewsletterSubscription::create([
                    'email' => $user->email,
                    'name' => $user->name,
                    'consent_rgpd' => true,
                    'consent_rgpd_at' => now(),
                    'source' => 'account_settings',
                    'ip_address' => $request->ip(),
                    'active' => true,
                ]);
            }
        } else {
            if ($subscription) {
                $subscription->unsubscribe();
            }
        }
        return back()->with('success', __('preferences_updated'));
    }
}
