<?php

namespace App\Http\Controllers;

use App\Mail\ContactAutoReplyMail;
use App\Mail\ContactAdminMail;
use App\Models\configurations;
use App\Models\Contact;
use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'subject' => 'required|string|max:255',
                'message' => 'required|string|max:2000',
                'consent_rgpd' => 'required|accepted',
                'g-recaptcha-response' => 'required',
            ]);

            $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ])->json();

            if (!($recaptchaResponse['success'] ?? false) || ($recaptchaResponse['score'] ?? 0) < 0.5) {
                Log::warning('reCAPTCHA verification failed', ['response' => $recaptchaResponse]);

                $error = __('Verification failed. Please try again.');

                if ($request->ajax()) {
                    return response()->json(['message' => $error], 422);
                }

                return back()->with('error', $error);
            }

            // --- STEP 1: Save the contact. This is the part that must never be lost. ---
            $contact = Contact::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'consent_rgpd' => true,
                'consent_rgpd_at' => now(),
                'consent_newsletter' => $request->has('consent_newsletter'),
                'newsletter_subscribed_at' => $request->has('consent_newsletter') ? now() : null,
                'user_id' => auth()->id(),
            ]);

            if ($request->has('consent_newsletter')) {
                NewsletterSubscription::updateOrCreate(
                    ['email' => $validated['email']],
                    [
                        'name' => $validated['name'],
                        'consent_rgpd' => true,
                        'consent_rgpd_at' => now(),
                        'source' => 'contact_form',
                        'ip_address' => $request->ip(),
                        'active' => true,
                        'subscribed_at' => now(),
                    ]
                );
            }

            // --- STEP 2: Try to send emails, but never let a mail failure fail the request ---
            $configEmail = optional(configurations::first())->email ?? config('mail.from.address');
            $configName = config('mail.from.name', 'Shopin');

            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'userMessage' => $validated['message'],
            ];

            $mailFailed = false;

            try {
                Mail::to($configEmail)->send(new ContactAdminMail($data));
                Log::info('Admin email sent', ['to' => $configEmail]);
            } catch (\Throwable $e) {
                $mailFailed = true;
                Log::error('Failed to send admin notification email', [
                    'contact_id' => $contact->id,
                    'to' => $configEmail,
                    'error' => $e->getMessage(),
                ]);
            }

            try {
                Mail::to($validated['email'])->send(new ContactAutoReplyMail($data));
                Log::info('Auto-reply sent', ['to' => $validated['email']]);
            } catch (\Throwable $e) {
                $mailFailed = true;
                Log::error('Failed to send auto-reply email', [
                    'contact_id' => $contact->id,
                    'to' => $validated['email'],
                    'error' => $e->getMessage(),
                ]);
            }

            Log::info('Contact form submitted', [
                'id' => $contact->id,
                'email' => $validated['email'],
                'consent_rgpd' => true,
                'consent_newsletter' => $request->has('consent_newsletter'),
                'ip' => $request->ip(),
                'mail_failed' => $mailFailed,
            ]);

            // --- STEP 3: Always tell the user it worked — their submission is safely stored ---
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Your message has been sent successfully!'),
                    'data' => ['id' => $contact->id],
                ]);
            }

            return back()->with('success', __('Your message has been sent successfully!'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;

        } catch (\Exception $e) {
            // At this point, something failed BEFORE Contact::create() — e.g. DB error.
            // This is the only case where the user should legitimately see an error.
            Log::error('Contact form submission failed before saving', [
                'error' => $e->getMessage(),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Une erreur est survenue: '.$e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Une erreur est survenue: '.$e->getMessage());
        }
    }
}
