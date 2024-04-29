<?php

namespace App\Livewire\Admin;

use App\Mail\SendMessage as MailSendMessage;
use App\Models\configurations;
use Exception;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class SendMessage extends Component
{
    public $email, $message, $sujet, $username;

    protected $listeners = ['sendDataUser'];

    public function sendDataUser($email, $username)
    {
        $this->email = $email;
        $this->username = $username;
    }

    public function render()
    {
        return view('livewire.admin.send-message');
    }

    public function envoyer()
    {

        $config = configurations::select("email_send_message")->first();
        if (!$config) {
            session()->flash('error', 'Une erreur est survenue !');
            return;
        }
        if ($config->email_send_message == "") {
            session()->flash('error', "Veuillez configurer le mail d'expedition.");
            return;
        }


        //validation
        $this->validate([
            'email' => 'required|email|exists:users,email',
            'message' => 'required|string',
            'sujet' => 'required|string|max:200'
        ], [
            'email.exists' => "Cet email n'est pas valide.",
            "required" =>  "Le champ :attribute est requis.",
            "string" =>   ":attribute doit être une chaîne de caractères.",
        ]);

        $message = [
            "destainataire" => $this->message,
            "message" => $this->message,
            "sujet" => $this->sujet,
            "email_send_message" => $config->email_send_message
        ];

        try {
            // Envoyer le message
            Mail::to($this->email)->send(new MailSendMessage($message));
            //flash success message en reset input
            session()->flash("success", "Votre message a été envoyé avec succès.");
            $this->sujet = "";
            $this->message = "";
        } catch (Exception $e) {
            // Flash message d'erreur
            session()->flash("error", "Une erreur s'est produite lors de l'envoi du message : " . $e->getMessage());
        }
    }
}
