<?php

namespace App\Livewire\Admin;

use App\Mail\SendMessage as MailSendMessage;
use App\Models\configurations;
use App\Models\motifs;
use App\Models\posts;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Events\UserEvent;
use App\Models\notifications;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;

use Livewire\Component;

class SendMessage extends Component
{
    public $email, $message, $sujet, $username, $post, $titre, $user_id, $gender, $post_id, $image, $sent_from;
    public $recipientEmail;
    protected $listeners = ['sendDataUser'];


    public function sendDataUser($user_id = null, $username = null, $titre = null, $post_id = null, $image = null, $sent_from = null)
    {
        $user = User::withTrashed()->find($user_id);
        if ($user) {
            // $this->recipientEmail = $user->email;
            // $this->username = $username;
            if ($user->trashed()) {
                $this->recipientEmail = $user->email_deleted;
                $this->username = $user->username_deleted;
            } else {
                $this->recipientEmail = $user->email;
                $this->username = $username ?: $user->username;
            }
            $this->user_id = $user_id;
            $this->gender = $user->gender;
            $this->titre = $titre;
            $this->post_id = $post_id;
            $this->image = $image;
            $this->sent_from = $sent_from;
        } else {
            session()->flash('error', 'User not found.');
        }
    }
    public function render()
    {
        return view('livewire.admin.send-message');
    }

    public function envoyer()
    {
        $user = Auth::user();
        if (!$user) {
            session()->flash('error', 'Vous devez être connecté pour envoyer un message.');
            return;
        }

        $this->validate([
            'message' => 'required|string',
            'sujet'   => 'required|string|max:200'
        ], [
            "required" => "Le champ :attribute est requis.",
            "string"   => ":attribute doit être une chaîne de caractères.",
        ]);

        $message = [
            "destainataire"      => $this->message,
            "message"            => $this->message,
            "sujet"              => $this->sujet,
            "email_send_message" => $user->email,
            "image"              => $this->image,
            "titre"              => $this->titre,
            "post_id"            => $this->post_id,
        ];

        try {
            Mail::to($this->recipientEmail)->send(new MailSendMessage($message));

            \App\Models\AdminMessage::create([
                'from_user_id' => Auth::id(),
                'to_user_id'   => $this->user_id,
                'sujet'        => $this->sujet,
                'message'      => $this->message,
                'post_id'      => $this->post_id,
                'sent_from'    => $this->sent_from,
            ]);

            if (!empty($this->post_id)) {
                // Set locale to the recipient's preferred language
                $recipient = User::find($this->user_id);
                $userLocale = $recipient->locale ?? config('app.locale');
                App::setLocale($userLocale);

                $genderKey = $this->gender === 'female' ? 'greeting_female' : 'greeting_male';
                $salutation = __($genderKey);

                $notification = new notifications();
                $notification->titre = __('message_received');
                $notification->id_user_destination = $this->user_id;
                $notification->type = "admin_message";
                $notification->url = "#";
                $notification->message = __('admin_message_notification_message', [
                    'salutation' => $salutation,
                    'username'   => $this->username,
                    'sujet'      => $this->sujet,
                    'post_id'    => $this->post_id,
                    'post_slug'  => Str::slug($this->titre),
                    'titre'      => $this->titre,
                    'message'    => $this->message,
                ]);
                $notification->save();

                App::setLocale(config('app.locale'));

                event(new UserEvent($this->user_id));

                // Send FCM notification
                $fcmService = app(\App\Services\FcmService::class);
                $sent = $fcmService->sendToUser(
                    $this->user_id,
                    "Nouveau message de l'equipe de Shopin !",
                    "$salutation " . $this->username . ", Vous avez reçu un message avec le sujet: " . $this->sujet,
                    [
                        'type'            => 'alerte',
                        'notification_id' => $notification->id,
                        'destination'     => 'user',
                        'action'          => 'admin_message',
                        'post_id'         => $this->post_id,
                    ]
                );

                if ($sent) {
                    \Log::info("FCM notification sent successfully", [
                        'user_id'         => $this->user_id,
                        'notification_id' => $notification->id,
                        'type'            => 'admin_message'
                    ]);
                } else {
                    \Log::warning("FCM notification failed to send", [
                        'user_id'         => $this->user_id,
                        'notification_id' => $notification->id,
                        'reason'          => 'User has no FCM token or token invalid'
                    ]);
                }
            }

            session()->flash("success", "Votre message a été envoyé avec succès.");
            $this->dispatch('alert', ['message' => "Votre message a été envoyé avec succès.", 'type' => 'success']);
            $this->dispatch('closeModal');
            $this->sujet   = "";
            $this->message = "";
        } catch (Exception $e) {
            session()->flash("error", "Une erreur s'est produite lors de l'envoi du message : " . $e->getMessage());
            $this->dispatch('alert', ['message' => "Une erreur s'est produite lors de l'envoi du message : " . $e->getMessage(), 'type' => 'error']);
        }
    }
}
