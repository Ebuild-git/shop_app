<?php

namespace App\Livewire\Admin;

use App\Mail\SendMessage as MailSendMessage;
use App\Models\configurations;
use App\Models\motifs;
use App\Models\posts;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class SendMessage extends Component
{
    public $email, $message, $sujet, $username,$post,$titre;

    protected $listeners = ['sendDataUser'];

    public function sendDataUser($id_post, $username)
    {
        $post = posts::where('id',$id_post)->first();
        $this->post = $post;
        $this->titre = $post->titre;
        $this->email = $post->user_info->email;
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
            "email_send_message" => $config->email_send_message
        ];

        try {
            //enregistrempent du message sur le motif du post
            $motifs = new motifs();
            $motifs->id_post = $this->post->id;
            $motifs->motif = $this->message;
            $motifs->id_user = $this->post->id_user;
            if(!$motifs->save()){
                session()->flash("error", "Une erreur s'est produite lors de l'envoi du message.");
                return;
            }

            // Envoyer le message par mail
            Mail::to($this->post->user_info->email)->send(new MailSendMessage($message));
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
