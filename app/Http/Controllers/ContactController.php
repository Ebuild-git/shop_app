<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\configurations;
use App\Models\{NewsletterSubscription, Contact};
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        try {
            $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'consent_rgpd' => 'required|accepted'
        ]);

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

        $configEmail = optional(configurations::first())->email ?? config('mail.from.address');
        $configName = config('mail.from.name', 'Shopin');

        $data = [
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'subject' => $validated['subject'],
            'userMessage' => $validated['message'],
        ];

        Mail::send('emails.contact_admin', $data, function ($mail) use ($validated, $configEmail, $configName) {
            $mail->to($configEmail)
                ->subject('[Contact] ' . $validated['subject'])
                ->from($configEmail, $configName);
        });

        Mail::send('emails.contact_autoreply', $data, function ($mail) use ($validated, $configEmail, $configName) {
            $mail->to($validated['email'])
                ->subject('Confirmation de votre message - Support Shopin')
                ->from($configEmail, $configName);
        });

        Log::info('Contact form submitted', [
            'id' => $contact->id,
            'email' => $validated['email'],
            'consent_rgpd' => true,
            'consent_newsletter' => $request->has('consent_newsletter'),
            'ip' => $request->ip()
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Votre message a été envoyé avec succès!',
                'data' => ['id' => $contact->id]
            ]);
        }

        return back()->with('success', __('Your message has been sent successfully!'));


        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;

        } catch (\Swift_TransportException $e) {
            // Capturer spécifiquement les erreurs SMTP
            $message = $e->getMessage();

            if (str_contains($message, 'Domain not found') ||
                str_contains($message, 'Recipient address rejected')) {
                $error = 'L\'adresse email de destination est invalide ou le domaine n\'existe pas. Veuillez vérifier l\'email et réessayer.';
            } elseif (str_contains($message, '450') || str_contains($message, '550')) {
                $error = 'Erreur serveur SMTP. Veuillez réessayer plus tard.';
            } else {
                $error = 'Erreur d\'envoi d\'email: ' . $message;
            }

            if ($request->ajax()) {
                return response()->json([
                    'message' => $error
                ], 500);
            }

            return back()->with('error', $error);

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Une erreur est survenue: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

}
