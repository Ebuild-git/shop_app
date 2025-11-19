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

use Livewire\Component;

class SendMessage extends Component
{
    public $email, $message, $sujet, $username,$post,$titre, $user_id, $gender, $post_id, $image;
    public $recipientEmail;
    protected $listeners = ['sendDataUser'];


    public function sendDataUser($user_id = null, $username = null, $titre = null, $post_id = null, $image = null)
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

        //validation
        $this->validate([
            'message' => 'required|string',
            'sujet' => 'required|string|max:200'
        ], [
            "required" =>  "Le champ :attribute est requis.",
            "string" =>   ":attribute doit être une chaîne de caractères.",
        ]);

        $message = [
            "destainataire" => $this->message,
            "message" => $this->message,
            "sujet" => $this->sujet,
            "email_send_message" => $user->email,
            "image" => $this->image,
            "titre" => $this->titre,
            "post_id" => $this->post_id,
        ];

        $salutation = 'Cher';

        if ($this->gender === 'female') {
            $salutation = 'Chère';
        }
        try {
            Mail::to($this->recipientEmail)->send(new MailSendMessage($message));
            if (!empty($this->post_id)) {
                $notification = new notifications();
                $notification->titre = "Nouveau message de l'equipe de Shopin !";
                $notification->id_user_destination = $this->user_id;
                $notification->type = "alerte";
                $notification->url = "#";
                $notification->message = "$salutation " . $this->username . ",<br>"
                . "Vous avez reçu un message avec le sujet suivant : <strong>{$this->sujet}</strong>.<br>"
                . "Pour l'article : <a href='/post/{$this->post_id}/" . Str::slug($this->titre) . "' class='underlined-link'>{$this->titre}</a>.<br>"
                . "Le contenu du message est : {$this->message}.<br>"
                . "Pour plus d'informations, n'hésitez pas à <a href='/contact' class='underlined-link'>nous contacter</a>.";
                $notification->save();
                event(new UserEvent($this->user_id));
            }
            session()->flash("success", "Votre message a été envoyé avec succès.");
            $this->dispatch('alert', ['message' => "Votre message a été envoyé avec succès.",'type'=>'success']);
            $this->dispatch('closeModal');
            $this->sujet = "";
            $this->message = "";
        } catch (Exception $e) {
            session()->flash("error", "Une erreur s'est produite lors de l'envoi du message : " . $e->getMessage());
            $this->dispatch('alert', ['message' => "Une erreur s'est produite lors de l'envoi du message : " . $e->getMessage(),'type'=>'error']);
        }
    }
}
