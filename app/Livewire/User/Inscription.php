<?php

namespace App\Livewire\User;

use App\Events\AdminEvent;
use App\Mail\VerifyMail;
use App\Models\configurations;
use App\Models\notifications;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;



class Inscription extends Component
{
    use WithFileUploads;
    public $nom, $email, $telephone, $password, $photo, $matricule, $username, $accept, $date, $genre, $prenom, $adress, $password_confirmation;
    public $jour, $mois, $annee;

    public function render()
    {
        return view('livewire.user.inscription');
    }


    public function updatedUsername($value)
    {
        $cleanedUsername = preg_replace('/[^A-Za-z0-9\-#]/', '', $value);
        $count = User::where("username", $cleanedUsername)->count();
        if ($count > 0) {
            $this->addError('username', "Ce nom d'utilisateur est déjà utilisé !");
        }
        $this->username = $cleanedUsername;
    }



    public function set_genre($val)
    {
        if ($val == "male" || $val == "female") {
            $this->genre = $val;
        }
    }



    public function inscription()
    {
        $validatedData = $this->validate([
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', 'string'],
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'matricule' => 'nullable|mimes:jpg,png,jpeg,pdf|max:2048',
            'nom' => ['required', 'string'],
            'prenom' => ['required', 'string'],
            'adress' => ['nullable', 'string'],
            'telephone' => ['required', 'numeric'],
            'username' => "string|unique:users,username",
            'genre' => 'required|in:female,male'
        ], [
            'required' => 'Ce champ est obligatoire.',
            'email' => 'Veuillez entrer une adresse email valide.',
            'unique' => 'Cette valeur est déjà utilisée.',
            'image' => 'Le fichier doit être une image.',
            'mimes' => 'Le fichier doit être au format jpg, png, jpeg ou webp.',
            'max' => 'La taille de l\'image ne doit pas dépasser 2 Mo.',
            'matricule.mimes' => 'Le fichier doit être au format jpg, png, jpeg ou pdf.',
            'telephone.numeric' => 'Le numéro de téléphone doit être un nombre.',
            'username.string' => 'Le nom d\'utilisateur doit être une chaîne de caractères.',
            'username.unique' => 'Ce nom d\'utilisateur est déjà utilisé.',
            'genre.in' => 'Le genre choisi n\'est pas valide.',
            'confirmed' => 'Les mots de passe ne correspondent pas.'

        ]);





        //generer un token pour la verification de mail
        $token = md5(time());


        //verifier en fonction de la date que l'utilisateur a minimun 13 ans et maximun 100 ans
        $dateString = $this->annee . "-" . $this->mois . "-" . $this->jour;
        $date = date_create_from_format('Y-m-d', $dateString);
        if ($date === false) {
            $this->addError('jour', 'Format de date incorrect');
            return;
        }
        // Calculer la différence entre l'année actuelle et l'année fournie
        $currentYear = date('Y');
        $yearOfBirth = (int) $date->format('Y');
        $age = $currentYear - $yearOfBirth;

        if ($age < 13) {
            $this->addError('jour', 'L\'âge minimal est de 13 ans');
            return;
        }


        if (!$this->genre) {
            $this->addError('genre', 'Choissisez votre genre');
            return;
        }

        $config = configurations::first();

        $user = new User();
        $user->lastname = $this->nom;
        $user->email = $this->email;
        $user->firstname = $this->prenom;
        $user->password = Hash::make($this->password);
        $user->phone_number = $this->telephone;
        $user->birthdate = $date;
        $user->gender = $this->genre;
        $user->role = "user";
        $user->type = "user";
        $user->address = $this->adress;
        $user->username = $this->username;
        if ($this->photo) {
            $newName = $this->photo->store('uploads/avatars', 'public');
            $user->avatar = $newName;
        } else {
            $user->avatar = null;
        }
        $user->ip_address = request()->ip();
        $user->remember_token = $token;

        if ($this->matricule) {
            $matricule = $this->matricule->store('uploads/documents', 'public');
            $user->type = "shop";
            $user->matricule = $matricule;
        }


        if ($config->valider_photo == 1) {
            if ($this->photo) {
                if ($user->save()) {
                    event(new AdminEvent('Un utilisateur a changé sa photo de profil'));
                    //enregistrer la notification
                    $notification = new notifications();
                    $notification->type = "photo";
                    $notification->titre = $user->name . " vient de choisir  une  photo de profil";
                    $notification->url = "/admin/client/" . $user->id . "/view";
                    $notification->message = "Le client a ajouté une photo de profil";
                    $notification->id_user = $user->id;
                    $notification->destination = "admin";
                    $notification->save();
                }
            }
        } else {
            $user->photo_verified_at = now();
        }


        $user->save();
        //donner le role user
        $user->assignRole('user');

        //envoi du mail avec le lien de validation
        Mail::to($user->email)->send(new VerifyMail($user, $token));

        return redirect("/connexion")->with("success", "Votre compte a été créé avec succès! Pour
        finaliser votre inscription, cliquez sur le lien de
        validation que nous vous avons envoyé par
        e-mail. Merci et bienvenue parmi nous !");
        //reset form

    }
}
