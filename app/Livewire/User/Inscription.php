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
    public $nom, $email, $telephone, $password, $photo, $matricule, $username, $accept,$date,$genre;

    public function render()
    {
        return view('livewire.user.inscription');
    }



    public function updatedUsername($value)
    {
        $cleanedUsername = preg_replace('/[^A-Za-z0-9]/', '', $value);
        $count = User::where("username", $cleanedUsername)->count();
        if ($count > 0) {
            $this->addError('username', "Ce nom d'utilisateur est déjà utilisé !");
        }
        $this->username = $cleanedUsername;
    }





    public function inscription()
    {
        $validatedData = $this->validate([
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'string'],
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'matricule' => 'nullable|mimes:jpg,png,jpeg,pdf|max:2048',
            'nom' => ['required', 'string'],
            'telephone' => ['required', 'numeric'],
            'accept' => ['required', 'accepted'],
            'username' => "string|unique:users,username",
            'date'=>['required'],
            'genre' =>'required|in:Féminin,Masculin'
        ], [
            'required' => 'Ce champ est obligatoire.',
            'email' => 'Veuillez entrer une adresse email valide.',
            'unique' => 'Cette valeur est déjà utilisée.',
            'image' => 'Le fichier doit être une image.',
            'mimes' => 'Le fichier doit être au format jpg, png, jpeg ou webp.',
            'max' => 'La taille de l\'image ne doit pas dépasser 2 Mo.',
            'matricule.mimes' => 'Le fichier doit être au format jpg, png, jpeg ou pdf.',
            'telephone.numeric' => 'Le numéro de téléphone doit être un nombre.',
            'accept.required' => 'Vous devez accepter les termes et conditions.',
            'accept.accept' => 'Vous devez accepter les termes et conditions pour continuer.',
            'username.string' => 'Le nom d\'utilisateur doit être une chaîne de caractères.',
            'username.unique' => 'Ce nom d\'utilisateur est déjà utilisé.',
            'genre.in' => 'Le genre choisi n\'est pas valide.',
        ]);





        //generer un token pour la verification de mail
        $token = md5(time());


        //verifier en fonction de la date que l'utilisateur a minimun 13 ans et maximun 100 ans
        if (Carbon::parse($this->date)->age < 13) {
            $this->addError('date', 'L\'âge minimal est de 13ans');
            return;
        }

        $config = configurations::first();

        $user = new User();
        $user->name = $this->nom;
        $user->email = $this->email;
        $user->password = Hash::make($this->password);
        $user->phone_number = $this->telephone;
        $user->naissance = $this->date;
        $user->genre = $this->genre;
        $user->role = "user";
        $user->type = "user";
        $user->username = $this->username;
        if ($this->photo) {
            $newName = $this->photo->store('uploads/avatars', 'public');
            $user->avatar = $newName;
        }else{
            $user->avatar = null;
        }
        $user->ip_adress = request()->ip();
        $user->remember_token = $token;

        if ($this->matricule) {
            $matricule = $this->matricule->store('uploads/documents', 'public');
            $user->type = "shop";
            $user->matricule = $matricule;
        } else {
            $user->validate_at = now();
        }

        
        if($config->valider_photo == 1)
        {
            if($this->photo){
                if($user->save()){
                    event(new AdminEvent('Un utilisateur a changé sa photo de profil'));
                    //enregistrer la notification
                    $notification = new notifications();
                    $notification->type = "photo";
                    $notification->titre = $user->name . " vient de choisir  une  photo de profil";
                    $notification->url = "/admin/client/". $user->id ."/view";
                    $notification->message = "Le client a ajouté une photo de profil";
                    $notification->id_user = $user->id;
                    $notification->destination = "admin";
                    $notification->save();
                }
                $user->photo_verified_at = null;
            }
        }else{
            $user->photo_verified_at= now();
        }


        $user->save();
        //donner le role user
        $user->assignRole('user');

        //envoi du mail avec le lien de validation
        Mail::to($user->email)->send(new VerifyMail($user, $token));

        return redirect("/connexion")->with("success", "Votre compte a bien été créé. Nous vous avons envoyé un email pour valider votre adresse e-mail.");
        //reset form

    }
}
