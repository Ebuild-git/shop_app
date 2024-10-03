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

use Livewire\Component;

class SendMessage extends Component
{
    public $email, $message, $sujet, $username,$post,$titre, $user_id;
    public $recipientEmail;
    protected $listeners = ['sendDataUser'];

    // public function sendDataUser($id_post, $username)
    // {
    //     $post = posts::where('id',$id_post)->first();
    //     $this->post = $post;
    //     $this->titre = $post->titre;
    //     $this->email = $post->user_info->email;
    //     $this->username = $username;
    // }
    public function sendDataUser($user_id, $username)
    {
        $user = User::find($user_id);
        if ($user) {
            $this->recipientEmail = $user->email;
            $this->username = $username;
            $this->user_id = $user_id;
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

        try {
            // Send the message via email
            Mail::to($this->recipientEmail)->send(new MailSendMessage($message));

            // Flash success message and reset inputs
            session()->flash("success", "Votre message a été envoyé avec succès.");
            $this->sujet = "";
            $this->message = "";
        } catch (Exception $e) {
            // Flash error message
            session()->flash("error", "Une erreur s'est produite lors de l'envoi du message : " . $e->getMessage());
        }
    }
}
