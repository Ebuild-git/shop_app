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

use Livewire\Component;

class SendMessage extends Component
{
    public $email, $message, $sujet, $username,$post,$titre, $user_id, $gender;
    public $recipientEmail;
    protected $listeners = ['sendDataUser'];


    public function sendDataUser($user_id, $username)
    {
        $user = User::find($user_id);
        if ($user) {
            $this->recipientEmail = $user->email;
            $this->username = $username;
            $this->user_id = $user_id;
            $this->gender = $user->gender;
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
            "email_send_message" => $user->email
        ];

        $salutation = 'Cher';

        if ($this->gender === 'female') {
            $salutation = 'Chère';
        }
        try {
            Mail::to($this->recipientEmail)->send(new MailSendMessage($message));

            $notification = new notifications();
            $notification->titre = "Nouveau message de l'equipe de Shopin !";
            $notification->id_user_destination = $this->user_id;
            $notification->type = "alerte";
            $notification->url = "#";
            $notification->message = "$salutation " . $this->username . ", "
            . "vous avez reçu un message avec le sujet suivant : <strong>{$this->sujet}</strong>. "
            . "Le contenu du message est : {$this->message}. "
            . "Pour plus d'informations, n'hésitez pas à <a href='/contact' class='underlined-link'>nous contacter</a>.";

            $notification->save();
            event(new UserEvent($this->user_id));


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
