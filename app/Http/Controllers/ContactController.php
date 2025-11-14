<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\configurations;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $configEmail = optional(configurations::first())->email ?? config('mail.from.address');
        $data = [
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'subject' => $validated['subject'],
            'userMessage' => $validated['message'],
        ];

        Mail::send('emails.contact_admin', $data, function ($mail) use ($validated, $configEmail) {
            $mail->to($configEmail)
                ->subject('[Contact] ' . $validated['subject'])
                ->from('shopin@ebuild.website', 'Contact Shopin');
        });


        Mail::send('emails.contact_autoreply', $data, function ($mail) use ($validated, $configEmail) {
            $mail->to($validated['email'])
                ->subject('Nous avons bien reçu votre message')
                ->from($configEmail, 'Support Shopin');
        });

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', __('Your message has been sent successfully!'));
    }

}
